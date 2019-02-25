interface IMALinks {}
interface IMACollection {
	page:number,
	page_count:number,
	page_size:number,
	total_items:number,
	_embedded: {
		items: [],
	}
	_links: IMALinks,
}
interface IMADate {
	date: number, 
	timezone: string,
	timezone_type: number,
}
interface IMAUser {
	id:number,
	name: string,
	_links: IMALinks, 
}
interface IMANote {
	id:number,
	text: string,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
	},
}
interface IMAOperation {
	id: number,
	name: string,
	description: string,
	created: IMADate,
	_links: IMALinks, 
}
interface IMAHintType {
	id: number,
	name: string,
	priority: number,
	description: string,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		operation: IMAOperation,
	},
}
interface IMAHint {
	id: number,
	name: string,
	description: string,
	priority: number,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		type: IMAHintType,
		owner: IMAUser,
		simulations: IMASimulation[],
	},
}
interface IMAComment {
	id:number,
	body: string,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
	},
}
interface IMASimulation {
	id: number,
	state: number,
	effect: string,
	prevention: string,
	who: string,
	when: IMADate,
	commentCount: number, 
	created: IMADate,
	_embedded: {
		owner: IMAUser,
		reasons: IMANote[],
		influences: IMANote[],
		suggestions: IMANote[],
	},
	_links: IMALinks, 
}

class MACollection {
	constructor() {
		this.items = []; 
		this.links = new MALinks({});;
	}

	isEmpty():boolean {
		return this.items.length == 0;
	}

	has(name:string):boolean {
		return this.links.has(name);
	}

	load(obj:IMACollection) {
		this.page  		 = obj.page;
		this.page_count  = obj.page_count;
		this.page_size   = obj.page_size;
		this.total_items = obj.total_items;
		this.links = new MALinks(obj._links);
		this.loaded 	 = true;
		for (var i = 0; i < obj._embedded.items.length; i++) {
			this.items.push(obj._embedded.items[i]);
		}
	}

	items:any[];
	links: MALinks;
	page: number;
	page_count:number;
	page_size:number;
	total_items:number;
	loaded: boolean = false;
}

class MALinks {
	constructor(obj: IMALinks) {
		this.keys = obj;
	}

	getHref(name:string='self'):string {
		if (this.keys[name]) {
			return this.keys[name].href;
		}
	}

	has(name:string):boolean {
		return typeof this.keys[name] !== "undefined";
	}

	keys:{}
}

class MAUser {
	constructor(obj: IMAUser) {
		this.id	   = obj.id;
		this.name  = obj.name;
		this.links = new MALinks(obj._links);
	}

	id: number;
	name:string;
	links: MALinks;
}

class MAOperation {
	constructor(obj: IMAOperation) {
		this.id = obj.id;
		this.name = obj.name;
		this.description = obj.description;
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
	}

	id: number;
	name: string;
	description: string;
	created: Date;
	links: MALinks;
}

class MAHintType {
	constructor(obj: IMAHintType) {
		this.id = obj.id;
		this.name = obj.name;
		this.priority = obj.priority;
		this.user = new MAUser(obj._embedded.owner);
		this.operation = new MAOperation(obj._embedded.operation);
		this.description = obj.description;
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
		};
	}

	id: number;
	name: string;
	priority: number;
	description: string;
	user: MAUser;
	operation: MAOperation;
	links: MALinks;
}

class MAHint {

	static fromJSON(obj: IMAHint): MAHint{
		let e = new MAHint();
		e.load(obj);
		return e;
	}

	constructor() {
		this.comments = new MACollection();
		this.simulations = [];
		this.created = new Date;
	}

	load(obj: IMAHint) {
		this.id = obj.id;
		this.name = obj.name;
		this.priority = obj.priority;
		this.type = new MAHintType(obj._embedded.type);
		this.user = new MAUser(obj._embedded.owner);
		this.description = obj.description;
		this.commentCount = obj.commentCount;
		this.links = new MALinks(obj._links);
		this.simulations = [];
		for (var i=0; i < obj._embedded.simulations.length; i++) {
			this.addSimulation(MASimulation.fromJSON(obj._embedded.simulations[i]));	
		}
	}

	addSimulation(obj: MASimulation) {
		obj.setHint(this);
		this.simulations.push(obj);
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.comments.items.push(obj);
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
			priority: this.priority,
			description: this.description,
			type: this.type ? this.type.id : 0,
			operation: this.type ? this.type.operation.id : 0,
		};
	}

	id: number;
	name: string;
	type: MAHintType;
	priority: number = 0;
	description: string;
	user: MAUser;
	commentCount: number;
	comments: MACollection;
	simulations: MASimulation[];
	created: Date;
	links: MALinks;
}

class MASimulation {

	static readonly NOT_PROCESSED = 0;
	static readonly IN_PROGRESS   = 1;
	static readonly FINISHED      = 2;
	static readonly NOT_NECESSARY = -1;
	static readonly CANCELLED	  = -2;

	static fromJSON(obj: IMASimulation): MASimulation{
		let s = new MASimulation();
		s.load(obj);
		return s;
	}

	constructor() {
		this.reasons = [];
		this.suggestions = [];
		this.influences = [];
		this.comments = new MACollection();
		this.created = new Date;
	}

	load(obj: IMASimulation) {
		this.id = obj.id;
		this.state = obj.state;
		this.prevention = obj.prevention;
		this.effect = obj.effect;
		this.commentCount = obj.commentCount;
		this.when = obj.when ? new Date(obj.when.date) : null;
		this.who = obj.who;
		this.user = new MAUser(obj._embedded.owner);
		this.created = new Date(obj.created.date);
		this.reasons = [];
		this.suggestions = [];
		this.influences = [];
		for (var i=0; i < obj._embedded.reasons.length; i++) {
			this.addReason(MANote.fromJSON(obj._embedded.reasons[i]));	
		}
		for (var i=0; i < obj._embedded.influences.length; i++) {
			this.addInfluence(MANote.fromJSON(obj._embedded.influences[i]));	
		}
		for (var i=0; i < obj._embedded.suggestions.length; i++) {
			this.addSuggestion(MANote.fromJSON(obj._embedded.suggestions[i]));	
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			state: this.state,
			who: this.who,
			when: this.when ? this.when.toString() : null,
			prevention: this.prevention,
			effect: this.effect,
			reasons: this.reasons,
			influences: this.influences,
			suggestions: this.suggestions,
		};
	}

	setHint(obj:MAHint) {
		this.hint = obj;
	}

	addReason(obj:MANote) {
		obj.setSimulation(this);
		this.reasons.push(obj);
	}
	addInfluence(obj:MANote) {
		obj.setSimulation(this);
		this.influences.push(obj);
	}
	addSuggestion(obj:MANote) {
		obj.setSimulation(this);
		this.suggestions.push(obj);
	}
	removeNote(obj:MANote) {
		var index;
		if ((index = this.reasons.indexOf(obj)) >= 0) {
			var res = this.reasons.splice(index, 1);
		}
		else if ((index = this.suggestions.indexOf(obj)) >= 0) {
			var res = this.suggestions.splice(index, 1);
		}
		else if ((index = this.influences.indexOf(obj)) >= 0) {
			var res = this.influences.splice(index, 1);
		}
		console.log(index, this, res);
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.comments.items.push(obj);
	}

	id: number;
	hint: MAHint;
	user: MAUser;
	who: string;
	when: Date; 
	effect: string;
	prevention: string;
	reasons: MANote[];
	influences: MANote[];
	suggestions: MANote[];
	comments: MACollection;
	commentCount: number;
	created: Date;
	links: MALinks;
	state: number = MASimulation.NOT_PROCESSED;
}

class MANote {

	static fromJSON(obj: IMANote): MANote{
		let s = new MANote();
		s.load(obj);
		return s;
	}

	constructor() {
		this.comments = new MACollection();
		this.created = new Date;
	}

	load(obj:IMANote) {
		this.id = obj.id;
		this.text = obj.text;
		this.commentCount = obj.commentCount;
		this.user = new MAUser(obj._embedded.owner);
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
	}

	setSimulation(obj:MASimulation) {
		this.simulation = obj;
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.comments.items.push(obj);
	}

	toJSON(): {}{
		return {
			id: this.id,
			text: this.text,
		};
	}

	id: number;
	text: string;
	user: MAUser;
	simulation: MASimulation;
	comments: MACollection;
	commentCount:number = 0;
	created: Date;
	links: MALinks;
}

class MAComment {
	constructor(obj: IMAComment) {
		this.id				= obj.id;
		this.body 			= obj.body;
		this.user 			= new MAUser(obj._embedded.owner);
		this.links 			= new MALinks(obj._links);
		this.created 		= new Date(obj.created.date);
		this.commentCount 	= obj.commentCount;
		this.children		= new MACollection();
	}

	/*addChildren(children: MAComment[]) {
		for (var i = 0; i < children.length; i++) {
			this.addChild(children[i]);
		}
	}*/

	addChild(child: MAComment) {
		child.parent = this;
		this.children.items.push(child);
	}

	hasChildren(): boolean {
		return !this.children.isEmpty();
	}

	getChildren():MACollection[] {
		return this.children.items;
	}

	id:number;
	body:string;
	commentCount:number;
	user: MAUser;
	parent: MAComment;
	children: MACollection;
	created: Date;
	links: MALinks;
}
