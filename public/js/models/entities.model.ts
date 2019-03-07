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

	isLoaded():boolean {
		return this.loaded;
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

class MAImage {
	constructor(obj: IMAImage) {
		this.id	   = obj.id;
		this.name  = obj.name;
		this.type  = obj.type;
		this.size  = obj.size;
		this.description  = obj.description;
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
			type: this.type,
			size: this.size,
			description:this.description
		};
	}

	id: number;
	stage: MAStage;
	name:string;
	type:string;
	size:string;
	description:string;
	links: MALinks;
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

class MAMaterial {
	constructor(obj: IMAMaterial) {
		this.id	   = obj.id;
		this.name  = obj.name;
		this.links = new MALinks(obj._links);
	}

	id: number;
	name:string;
	links: MALinks;
}

class MAProcess {

	static readonly COMPLEXITY_LOW    = "AA";
	static readonly COMPLEXITY_MEDIUM = "BB";
	static readonly COMPLEXITY_HIGH   = "A";

	static fromJSON(obj: IMAProcess): MAProcess{
		let e = new MAProcess();
		e.load(obj);
		return e;
	}

	constructor() {
		this.created = new Date;
		this.versions = [];
	}

	load(obj: IMAProcess) {
		this.id = obj.id;
		this.title= obj.title;
		this.body= obj.body;
		this.number= obj.number;
		this.code= obj.code;
		this.line= obj.line;
		this.machine= obj.machine;
		this.plant= obj.plant;
		this.complexity= obj.complexity;
		this.pieceNumber= obj.pieceNumber;
		this.pieceName= obj.pieceName;
		this.user = new MAUser(obj._embedded.owner);
		this.customer = new MAUser(obj._embedded.customer);
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
		this.versions = [];
		for (var i=0; i < obj._embedded.versions.length; i++) {
			this.addVersion(MAVersion.fromJSON(obj._embedded.versions[i]));	
		}
	}

	toJSON(): {}{
		return {
			id: this.id,
			title: this.title,
			body: this.body,
			number: this.number,
			code: this.code,
			line: this.line,
			machine: this.machine,
			plant: this.plant,
			complexity: this.complexity,
			pieceNumber: this.pieceNumber,
			pieceName: this.pieceNumber,
			user: this.user.id,
			customer: this.customer.id,
		};
	}

	hasVersions():boolean {
		return this.versions.length > 0;
	}

	addVersion(obj: MAVersion) {
		obj.setProcess(this);
		this.versions.push(obj);
	}

	removeVersion(obj: MAVersion) {
		var i:number;
		if (-1 !== (i = this.versions.indexOf(obj))) {
			this.versions.splice(i, 1);
		}
	}

	getActive():MAVersion {
		return this.versions[this.versions.length-1];
	}

	id: number;
	title: string;
	body: string;
	number: number;
	code: number;
	line: number;
	machine: string;
	plant: string;
	complexity: string;
	pieceNumber: string;
	pieceName: string;
	versions: MAVersion[];
	user: MAUser;
	customer: MAUser;
	created: Date;
	links: MALinks;
}

class MAVersion {
	static fromJSON(obj: IMAVersion): MAVersion{
		let e = new MAVersion();
		e.load(obj);
		return e;
	}

	constructor() {
		this.stages   = [];
		this.comments = new MACollection();
		this.created  = new Date;
	}

	load(obj: IMAVersion) {
		this.id = obj.id;
		this.name = obj.name;
		this.description = obj.description;
		this.commentCount = obj.commentCount;
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
		this.material = new MAMaterial(obj._embedded.material);
		this.user = new MAUser(obj._embedded.owner);
	}

	toJSON(): {}{
		var stages:{}[] = [];
		for (var i=0; i < this.stages.length; i++) {
			stages.push(this.stages[i].toJSON());
		}
		return {
			id: this.id,
			name: this.name,
			description: this.description,
			material: this.material ? this.material.id : null,
			stages: stages,
		};
	}

	setProcess(obj:MAProcess) {
		this.process = obj;
	}

	isStagesLoaded():boolean {
		return this.stagesLoaded;
	}

	addStage(obj:MAStage) {
		obj.version = this;
		this.stages.push(obj);
	}

	removeStage(obj: MAStage) {
		var i:number;
		if (-1 !== (i = this.stages.indexOf(obj))) {
			this.stages.splice(i, 1);
		}
	}

	hasStages():boolean {
		return this.stages.length > 0;
	}

	getActive():MAStage {
		return this.stages[this.stages.length-1];
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	id: number;
	name: string;
	description: string;
	process: MAProcess;
	user: MAUser;
	material: MAMaterial;
	stages: MAStage[];
	comments: MACollection;
	commentCount: number=0;
	created: Date;
	links: MALinks;
	stagesLoaded: boolean=false;
}

class MAStage {
	static fromJSON(obj: IMAStage): MAStage{
		let e = new MAStage();
		e.load(obj);
		return e;
	}

	constructor() {
		this.hints 		= [];
		this.operations = [];
		this.images 	= [];
		this.comments 	= new MACollection();
		this.created  	= new Date;
	}

	load(obj: IMAStage) {
		this.id = obj.id;
		this.order = obj.order;
		this.body = obj.body;
		this.commentCount = obj.commentCount;
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
		this.user = new MAUser(obj._embedded.owner);
		this.operations = [];
		this.images = [];
		for (var i=0; i < obj._embedded.images.length; i++) {
			this.addImage(new MAImage(obj._embedded.images[i]));
		}
		for (var i=0; i < obj._embedded.operations.length; i++) {
			this.operations.push(MAOperation.fromJSON(obj._embedded.operations[i]));
		}
	}

	toJSON(): {}{
		var operations: {}[] = [];
		for (var i=0; i < this.operations.length; i++) {
			operations[i] = {id: this.operations[i].id};
		}
		return {
			id: this.id,
			order: this.order,
			name: this.name,
			body: this.body,
			images: this.images,
			operations: operations,
			user: this.user ? this.user.id : null,
		};
	}

	setProcess(obj:MAProcess) {
		this.process = obj;
	}

	get name():string {
		return "Stage " + this.order;
	}

	addHint(obj:MAHint) {
		obj.stage = this;
		this.hints.push(obj);
	}

	addImage(obj:MAImage) {
		obj.stage = this;
		this.images.push(obj);
	}

	isHintsLoaded():boolean {
		return this.hintsLoaded;
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		obj.source = this;
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	id: number;
	order:number = 0;
	body: string;
	process: MAProcess;
	user: MAUser;
	version: MAVersion;
	operations: MAOperation[];
	images: MAImage[];
	hints: MAHint[];
	comments: MACollection;
	commentCount: number=0;
	created: Date;
	hintsLoaded: boolean=false;
	links: MALinks;
}

class MAOperationType {

	static fromJSON(obj: IMAOperationType): MAOperationType{
		let e = new MAOperationType;
		e.load(obj);
		return e;
	}

	constructor() {
		this.created = new Date;
		this.operations = [];
	}

	load(obj: IMAOperationType) {
		this.id = obj.id;
		this.text = obj.text;
		this.name = obj.text;
		this.description = obj.description;
		this.user = new MAUser(obj._embedded.owner);
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
		this.operations = [];
		for (var i=0; i < obj._embedded.operations.length; i++) {
			this.addOperation(MAOperation.fromJSON(obj._embedded.operations[i]));	
		}
	}

	toJSON(): {}{
		return {
			id: this.id,
			text: this.name,
			description: this.description,
		};
	}

	addOperation(obj: MAOperation) {
		obj.type = this;
		this.operations.push(obj);
	}

	id: number;
	text: string;
	user: MAUser;
	name: string;
	description: string;
	operations: MAOperation[];
	created: Date;
	links: MALinks;
}

class MAOperation {

	static fromJSON(obj: IMAOperation): MAOperation{
		let e = new MAOperation();
		e.load(obj);
		return e;
	}

	constructor() {
		this.hints 	  = [];
		this.parents  = [];
		this.children = [];
		this.created  = new Date;
	}

	load(obj: IMAOperation) {
		this.id = obj.id;
		this.name = obj.name;
		this.longName = obj.longName;
		this.description = obj.description;
		this.links = new MALinks(obj._links);
		this.children = [];
		if (obj._embedded) {
			this.created = new Date(obj.created.date);
			this.user = new MAUser(obj._embedded.owner);
			for (var i=0; i < obj._embedded.children.length; i++) {
				this.addChild(MAOperation.fromJSON(obj._embedded.children[i]));	
			}
		}
	}

	toJSON(): {}{
		var children:{}[] = [];
		for (var i=0; i < this.children.length; i++) {
			children.push(this.children[i].toJSON());	
		}
		return {
			id: this.id,
			name: this.name,
			description: this.description,
			children: children,
		};
	}

	addHint(obj: MAHintType) {
		obj.operation = this;
		this.hints.push(obj);
	}

	addChild(obj: MAOperation) {
		obj.parents.push(this);
		this.children.push(obj);
	}

	id: number;
	name: string;
	longName: string;
	description: string;
	user: MAUser;
	type: MAOperationType=null;
	parents: MAOperation[];
	children: MAOperation[];
	hints: MAHintType[];
	created: Date;
	links: MALinks;
}

class MAHintType {
	static fromJSON(obj: IMAHintType): MAHintType{
		let e = new MAHintType();
		e.load(obj);
		return e;
	}

	constructor() {
	}

	load(obj: IMAHintType) {
		this.id = obj.id;
		this.name = obj.name;
		this.priority = obj.priority;
		this.user = new MAUser(obj._embedded.owner);
		this.operation = MAOperation.fromJSON(obj._embedded.operation);
		this.description = obj.description;
		this.created = new Date(obj.created.date);
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			priority: this.priority,
			name: this.name,
			description: this.description,
		};
	}

	id: number;
	name: string;
	priority: number=0;
	description: string;
	user: MAUser;
	created: Date;
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
		this.type = MAHintType.fromJSON(obj._embedded.type);
		this.user = new MAUser(obj._embedded.owner);
		this.description = obj.description;
		this.commentCount = obj.commentCount;
		this.created = new Date(obj.created.date);
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
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
			priority: this.priority,
			description: this.description,
			type: this.type ? this.type.id : null,
			operation: this.type ? this.type.operation.id : null,
		};
	}

	id: number;
	name: string;
	stage: MAStage;
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

	get name():string {
		return this.hint.name + ' | Simulation';
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
		this.commentCount++;
		this.comments.items.unshift(obj);
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

	get name():string {
		return this.simulation.name + ' | Note';
	}

	setSimulation(obj:MASimulation) {
		this.simulation = obj;
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.commentCount++;
		this.comments.items.unshift(obj);
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
		this.children 		= new MACollection();
	}

	toJSON():{} {
		return {
			id: this.id,
			body: this.body,
		};
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
	source:any;
	commentCount:number=0;
	user: MAUser;
	parent: MAComment;
	children: MACollection;
	created: Date;
	links: MALinks;
}
