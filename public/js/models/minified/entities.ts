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

	removeElement(el:any) {
		if (this.items.splice(this.items.indexOf(el), 1)) {
			this.total_items--;
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
		if (this.has(name)) {
			return this.keys[name].href;
		}
	}

	isAllowed(name:string):boolean {
		if (this.has(name)) {
			return this.keys[name].allowed;
		}
		return false;
	}

	has(name:string):boolean {
		return typeof this.keys[name] !== "undefined";
	}

	keys:{}
}

class MAImage {
	constructor(obj: IMAImage) {
		if (obj.id) {
			this.id	   = obj.id;
			this.name  = obj.name;
			this.type  = obj.type;
			this.size  = obj.size;
			this.description  = obj.description;
			this.created = new Date(obj.created.date);
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
			type: this.type,
			size: this.size,
			description:this.description,
			created:this.created,
		};
	}

	id: number;
	source: any;
	name:string;
	type:string;
	size:string;
	description:string;
	created:Date;
	links: MALinks;
}

class MAAction {

	static fromJSON(obj: IMAAction): MAAction{
		let e = new MAAction();
		e.load(obj);
		return e;
	}

	load(obj: IMAAction) {
		if (obj.id) {
			this.id	     = obj.id;
			this.name    = obj.name;
			this.class   = obj.class;
			this.content = obj.content;
			this.user 	 = new MAUser(obj._embedded.owner);
			this.process = MAProcess.fromJSON(obj._embedded.process);
			this.created = new Date(obj.created.date);
			this.links   = new MALinks(obj._links);
			switch (this.class) {
				case "MA\\Entity\\Action\\Note":
					this.source = MANote.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\Process":
					this.source = MAProcess.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\Version":
					this.source = MAVersion.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\Stage":
					this.source = MAStage.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\Hint":
					this.source = MAHint.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\HintReason":
					this.source = MAHintReason.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\HintInfluence":
					this.source = MAHintInfluence.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Action\\Simulation":
					this.source = MASimulation.fromJSON(obj._embedded.source);
					break;	
			}
		}
	}

	constructor() {
		this.created = new Date;
	}

	id: number;
	name:string;
	class:string;
	content:{};
	source:any;
	process:MAProcess;
	user:MAUser;
	created:Date;
	links: MALinks;
}

class MAUser {
	constructor(obj: IMAUser) {
		this.id	   = obj.id;
		this.name  = obj.name;
		this.roles = obj.roles;
		this.links = new MALinks(obj._links);
	}

	id: number;
	name:string;
	roles:string[];
	links: MALinks;
}

class MAVersionType {
	constructor(obj: IMAVersionType) {
		this.id	   		  = obj.id;
		this.name  		  = obj.name;
		this.description  = obj.description;
		this.links 		  = new MALinks(obj._links);
	}

	id: number;
	name:string;
	description:string;
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
		if (obj.id) {
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
			this.versions = [];
			for (var i=0; i < obj._embedded.versions.length; i++) {
				this.addVersion(MAVersion.fromJSON(obj._embedded.versions[i]));	
			}
			this.reloadVersions();
		}
		this.links = new MALinks(obj._links);
	}

	reloadVersions() {
		this.versions.forEach(version => version.children = []); 
		this.versions.forEach(version => {
			if (version.hasParent()) {
				this.versions.find(e => {return e.id == version.parent.id}).addChild(version);
			}
		});
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
			pieceName: this.pieceName,
			user: this.user.id,
			customer: this.customer.id,
		};
	}

	static complexityLabel(value:string):string {
		switch (value) {
			case MAProcess.COMPLEXITY_LOW: 	  return "LOW";
			case MAProcess.COMPLEXITY_MEDIUM: return "MEDIUM";
			case MAProcess.COMPLEXITY_HIGH:   return "HIGH";
			default: return "-";
		}
	}

	complexityLabel():string {
		return MAProcess.complexityLabel(this.complexity);
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

	parentVersions():MAVersion[] {
		return this.versions.filter(version => version.isParent());
	}

	getActive():MAVersion {
		let parents = this.parentVersions();
		if (parents.length) {
			let last = parents[parents.length-1];
			while (last.hasChildren()) {
				last = last.children[last.children.length-1];
			}
			return last;
		}
	}

	id: number;
	title: string;
	body: string;
	number: string;
	code: string;
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
	static readonly STATE_IN_PROGRESS = 0;
	static readonly STATE_APPROVED    = 1;
	static readonly STATE_CANCELLED	= -1;

	static stateLabel(value:number):string {
		switch (value) {
			case MAVersion.STATE_IN_PROGRESS:  	return "In progress";
			case MAVersion.STATE_APPROVED: 		return "Approved";
			case MAVersion.STATE_CANCELLED:   	return "Cancelled";
			default: return "-";
		}
	}

	static fromJSON(obj: IMAVersion): MAVersion{
		let e = new MAVersion();
		e.load(obj);
		return e;
	}

	constructor() {
		this.stages   = [];
		this.children = [];
		this.comments = new MACollection();
		this.created  = new Date;
	}

	load(obj: IMAVersion) {
		if (obj.id) {
			this.id = obj.id;
			this.name = obj.name;
			this.state = obj.state;
			this.description = obj.description;
			this.commentCount = obj.commentCount;
			this.created = new Date(obj.created.date);
			this.material = new MAMaterial(obj._embedded.material);
			this.type = new MAVersionType(obj._embedded.type);
			this.user = new MAUser(obj._embedded.owner);
			this.parent = obj._embedded.parent ? MAVersion.fromJSON(obj._embedded.parent) : null;
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		var stages:{}[] = [];
		for (var i=0; i < this.stages.length; i++) {
			stages.push(this.stages[i].toJSON());
		}
		return {
			id: this.id,
			name: this.name,
			state: this.state,
			description: this.description,
			material: this.material ? this.material.id : null,
			type: this.type ? this.type.id : null,
			parent: this.parent ? this.parent.id : null,
			stages: stages,
		};
	}

	stateLabel():string {
		return MAVersion.stateLabel(this.state);
	}

	stateColor():string {
		switch (this.state) {
			case MAVersion.STATE_APPROVED: 	return 'success'; 
			case MAVersion.STATE_CANCELLED:	return 'danger'; 
			default: return 'dark';
		}
	}

	setProcess(obj:MAProcess) {
		this.process = obj;
	}

	isStagesLoaded():boolean {
		return this.stagesLoaded;
	}

	addChild(version:MAVersion) {
		version.parent = this;
		this.children.push(version);
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

	hasParent():boolean {
		return this.parent != null;
	}

	isParent():boolean {
		return !this.hasParent();
	}

	hasChildren():boolean {
		return this.children.length > 0;
	}

	hasStages():boolean {
		return this.stages.length > 0;
	}

	isFirst(obj:MAStage): boolean {
		return this.stages.length && this.stages[0] === obj;
	}

	isLast(obj:MAStage): boolean {
		return this.stages.length && this.stages[this.stages.length-1] === obj;
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
	parent: MAVersion;
	children: MAVersion[];
	material: MAMaterial;
	type: MAVersionType;
	stages: MAStage[];
	comments: MACollection;
	commentCount: number=0;
	created: Date;
	links: MALinks;
	state: number = MAVersion.STATE_IN_PROGRESS;
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
		if (obj.id) {
			this.id = obj.id;
			this.order = obj.order;
			this.body = obj.body;
			this.commentCount = obj.commentCount;
			this.created = new Date(obj.created.date);
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
		this.links = new MALinks(obj._links);
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

	get description():string {
		return this.body;
	}

	getHints():MAHint[] {
		return this.hints.sort((a, b) => {
			if (a.priority < b.priority) return 1;
			else if (a.priority > b.priority) return -1;
			else return 0;
		});	
	}

	addHints(items:MAHint[]) {
		for (var i=0; i < items.length; i++) {
			this.addHint(items[i]);
		}
	}

	addHint(obj:MAHint) {
		obj.stage = this;
		this.hints.push(obj);
	}

	addImage(obj:MAImage) {
		obj.source = this;
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
		if (obj.id) {
			this.id = obj.id;
			this.text = obj.text;
			this.name = obj.text;
			this.description = obj.description;
			this.user = new MAUser(obj._embedded.owner);
			this.created = new Date(obj.created.date);
			this.operations = [];
			for (var i=0; i < obj._embedded.operations.length; i++) {
				this.addOperation(MAOperation.fromJSON(obj._embedded.operations[i]));	
			}
		}
		this.links = new MALinks(obj._links);
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

	removeOperation(obj: MAOperation) {
		obj.type = null;
		this.operations.splice(this.operations.indexOf(obj), 1);
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
		if (obj._embedded) {
			this.id = obj.id;
			this.name = obj.name;
			this.color = obj.color;
			this.priority = obj.priority;
			this.user = new MAUser(obj._embedded.owner);
			this.operation = MAOperation.fromJSON(obj._embedded.operation);
			this.description = obj.description;
			this.created = new Date(obj.created.date);
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			priority: this.priority,
			name: this.name,
			color: this.color,
			description: this.description,
		};
	}

	id: number;
	name: string;
	color: string;
	priority: number=0;
	description: string;
	user: MAUser;
	created: Date;
	operation: MAOperation;
	links: MALinks;
}

class MAHintRelation {
	static fromJSON(obj: IMAHintRelation): MAHintRelation{
		let e = new MAHintRelation();
		e.load(obj);
		return e;
	}

	constructor() {
		this.comments = new MACollection();
	}

	load(obj: IMAHintRelation) {
		if (obj.id) {
			this.id = obj.id;
			this.description = obj.description;
			this.commentCount = obj.commentCount;
			this.user = new MAUser(obj._embedded.owner);
			this.source = MAHintReasonRel.fromJSON(obj._embedded.source);
			this.relation = MAHintInfluenceRel.fromJSON(obj._embedded.relation);
			this.created = new Date(obj.created.date);
		}
		this.links = new MALinks(obj._links);
	}

	get name():string {
		if (this.reason) return this.reason.name;
		else if (this.influence) return this.influence.name;
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
			description: this.description,
			source: this.source ? this.source.toJSON() : {},
			relation: this.relation ? this.relation.toJSON() : {},
		}
	}

	id:number;
	description:string;
	user:MAUser;
	reason: MAHintReason;
	influence: MAHintInfluence;
	created: Date;
	source: MAHintReasonRel;
	relation: MAHintInfluenceRel;
	commentCount: number=0;
	comments: MACollection;
	links: MALinks;
}

class MAHintReasonRel {
	static fromJSON(obj: IMAHintReasonRel): MAHintReasonRel{
		let e = new MAHintReasonRel();
		e.load(obj);
		return e;
	}

	constructor() {
	}

	setReason(obj:MAHintReason) {
		this.id = obj.id;
		this.hint = obj.hint;
		this.stage = obj.hint.stage;
	}

	load(obj: IMAHintReasonRel) {
		if (obj.id) {
			this.id = obj.id;
			this.stage = MAStage.fromJSON(obj._embedded.stage);
			this.hint = MAHint.fromJSON(obj._embedded.hint);
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			hint: this.hint ? this.hint.id : null,
			stage: this.stage ? this.stage.id : null,
		}
	}

	id:number;
	stage: MAStage;
	hint: MAHint;
	links: MALinks;
}

class MAHintInfluenceRel {
	static fromJSON(obj: IMAHintInfluenceRel): MAHintInfluenceRel{
		let e = new MAHintInfluenceRel();
		e.load(obj);
		return e;
	}

	constructor() {
		this.reason = new MAHintReasonRel();
	}

	load(obj: IMAHintInfluenceRel) {
		if (obj.id) {
			this.id = obj.id;
			this.reason = MAHintReasonRel.fromJSON(obj._embedded.reason);
		}
		this.links = new MALinks(obj._links);
	}

	get hint():MAHint {
		return this.reason.hint;
	} 

	get stage():MAStage {
		return this.reason.stage;
	} 

	toJSON(): {}{
		return {
			id: this.id,
			reason: this.reason,
		}
	}

	id:number;
	reason:MAHintReasonRel;
	links: MALinks;
}

class MAHintReason {
	static fromJSON(obj: IMAHintReason): MAHintReason{
		let e = new MAHintReason();
		e.load(obj);
		return e;
	}

	constructor() {
		this.notes      = [];
		this.relations 	= [];
		this.influences = [];
		this.comments   = new MACollection();
		this.created    = new Date;
	}

	load(obj: IMAHintReason) {
		if (obj._embedded) {
			this.id 	 	  = obj.id;
			this.created 	  = new Date(obj.created.date);
			this.user    	  = new MAUser(obj._embedded.owner);
			this.commentCount = obj.commentCount;
			this.notes 	      = [];
			for (var i=0; i < obj._embedded.notes.length; i++) {
				this.addNote(MANote.fromJSON(obj._embedded.notes[i]));	
			}
			for (var i=0; i < obj._embedded.influences.length; i++) {
				this.addInfluence(MAHintInfluence.fromJSON(obj._embedded.influences[i]));	
			}
			for (var i=0; i < obj._embedded.relations.length; i++) {
				if (obj._embedded.relations[i].id) {
					this.addRelation(MAHintRelation.fromJSON(obj._embedded.relations[i]));	
				}
			}
		}
		this.links = new MALinks(obj._links);
	}

	get name():string {
		return this.hint.name;
	}

	addNote(obj:MANote) {
		obj.setSource(this);
		this.notes.push(obj);
	}

	removeNote(obj:MANote) {
		var index:number;
		if ((index = this.notes.indexOf(obj)) >= 0) {
			var res = this.notes.splice(index, 1);
		}
	}

	addRelation(obj:MAHintRelation) {
		obj.reason = this;
		this.relations.push(obj);
	}

	addInfluence(obj:MAHintInfluence) {
		obj.setReason(this);
		this.influences.push(obj);
	}

	getSimulations(): MASimulation[] {
		let items:MASimulation[] = [];
		for (var i=0; i < this.influences.length; i++) {
			items = items.concat(this.influences[i].simulations);
		}
		return items;
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
			hint: this.hint ? this.hint.id : null,
			notes: this.notes,
			relations: this.relations,
			influences: this.influences,
		};
	}

	id: number;
	hint: MAHint;
	comments: MACollection;
	commentCount: number=0;
	notes: MANote[];
	relations:MAHintRelation[];
	influences: MAHintInfluence[];
	user: MAUser;
	created: Date;
	links: MALinks;
}

class MAHintInfluence {
	static fromJSON(obj: IMAHintInfluence): MAHintInfluence{
		let e = new MAHintInfluence();
		e.load(obj);
		return e;
	}

	constructor() {
		this.notes    	  = [];
		this.relations 	  = [];
		this.simulations  = [];
		this.comments     = new MACollection();
		this.created      = new Date;
	}

	load(obj: IMAHintInfluence) {
		if (obj._embedded) {
			this.id 	 	  = obj.id;
			this.created 	  = new Date(obj.created.date);
			this.user    	  = new MAUser(obj._embedded.owner);
			this.commentCount = obj.commentCount;
			this.notes 	      = [];
			this.simulations  = [];
			this.relations    = [];
			for (var i=0; i < obj._embedded.notes.length; i++) {
				this.addNote(MANote.fromJSON(obj._embedded.notes[i]));	
			}
			for (var i=0; i < obj._embedded.simulations.length; i++) {
				this.addSimulation(MASimulation.fromJSON(obj._embedded.simulations[i]));	
			}
			for (var i=0; i < obj._embedded.relations.length; i++) {
				if (obj._embedded.relations[i].id) {
					this.addRelation(MAHintRelation.fromJSON(obj._embedded.relations[i]));	
				}
			}
		}
		this.links = new MALinks(obj._links);
	}

	get name():string {
		return this.reason.name;
	}

	get hint():MAHint {
		return this.reason.hint;
	}

	setReason(obj:MAHintReason) {
		this.reason = obj;
	}

	addRelation(obj:MAHintRelation) {
		obj.influence = this;
		this.relations.push(obj);
	}

	addNote(obj:MANote) {
		obj.setSource(this);
		this.notes.push(obj);
	}

	removeNote(obj:MANote) {
		var index:number;
		if ((index = this.notes.indexOf(obj)) >= 0) {
			var res = this.notes.splice(index, 1);
		}
	}

	addSimulation(obj: MASimulation) {
		obj.influence = this;
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
			notes: this.notes,
			relations: this.relations,
			simulations: this.simulations,
		};
	}

	id: number;
	comments: MACollection;
	commentCount: number=0;
	reason: MAHintReason;
	notes: MANote[];
	relations:MAHintRelation[];
	simulations: MASimulation[];
	user: MAUser;
	created: Date;
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
		this.reasons  = [];
		this.created  = new Date;
	}

	load(obj: IMAHint) {
		if (obj._embedded) {
			this.id = obj.id;
			this.name = obj.name;
			this.color = obj.color;
			this.priority = obj.priority;
			this.type = MAHintType.fromJSON(obj._embedded.type);
			this.operation = MAOperation.fromJSON(obj._embedded.operation);
			this.user = new MAUser(obj._embedded.owner);
			this.description = obj.description;
			this.commentCount = obj.commentCount;
			this.stageOrder = obj.stageOrder;
			this.created = new Date(obj.created.date);
			for (var i=0; i < obj._embedded.reasons.length; i++) {
				this.addReason(MAHintReason.fromJSON(obj._embedded.reasons[i]));	
			}
		}
		this.links = new MALinks(obj._links);
	}

	addReason(obj:MAHintReason) {
		obj.hint = this;
		this.reasons.push(obj);
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	getInfluences(): MAHintInfluence[] {
		let items:MAHintInfluence[] = [];
		for (var i=0; i < this.reasons.length; i++) {
			items = items.concat(this.reasons[i].influences);
		}
		return items;
	}

	getSimulations(): MASimulation[] {
		let items:MASimulation[] = [];
		let infls:MAHintInfluence[] = this.getInfluences();
		for (var i=0; i < infls.length; i++) {
			items = items.concat(infls[i].simulations);
		}
		return items;
	}

	toJSON(): {}{
		return {
			id: this.id,
			name: this.name,
			color: this.color,
			stageOrder: this.stageOrder,
			priority: this.priority,
			description: this.description,
			type: this.type ? this.type.id : null,
			operation: this.operation ? this.operation.id : null,
		};
	}

	id: number;
	name: string;
	color: string;
	stage: MAStage;
	stageOrder: number;
	type: MAHintType;
	operation: MAOperation;
	priority: number = 0;
	description: string;
	user: MAUser;
	commentCount: number;
	comments: MACollection;
	reasons: MAHintReason[];
	created: Date;
	links: MALinks;
}

class MASimulation {

	static readonly STATE_NOT_PROCESSED = 0;
	static readonly STATE_IN_PROGRESS   = 1;
	static readonly STATE_FINISHED      = 2;
	static readonly STATE_NOT_NECESSARY = -1;
	static readonly STATE_CANCELLED	  = -2;

	static fromJSON(obj: IMASimulation): MASimulation{
		let s = new MASimulation();
		s.load(obj);
		return s;
	}

	static stateLabel(value:number):string {
		switch (value) {
			case MASimulation.STATE_NOT_NECESSARY:  	return "No necessary";
			case MASimulation.STATE_NOT_PROCESSED:  	return "No processed";
			case MASimulation.STATE_IN_PROGRESS:  		return "In progress";
			case MASimulation.STATE_FINISHED:  			return "Finished";
			case MASimulation.STATE_CANCELLED:  		return "Cancelled";
			default: return "-";
		}
	}

	stateLabel():string {
	return MASimulation.stateLabel(this.state);
	}

	constructor() {
		this.effects = [];
		this.suggestions = [];
		this.preventions = [];
		this.images	= [];
		this.comments = new MACollection();
		this.created = new Date;
	}

	load(obj: IMASimulation) {
		if (obj.id) {
			this.id = obj.id;
			this.state = obj.state;
			this.commentCount = obj.commentCount;
			this.when = obj.when ? new Date(obj.when.date) : null;
			this.who = obj._embedded.who ? new MAUser(obj._embedded.who) : null;
			this.user = new MAUser(obj._embedded.owner);
			this.created = new Date(obj.created.date);
			this.effects = [];
			this.suggestions = [];
			this.preventions = [];
			this.images = [];
			for (var i=0; i < obj._embedded.images.length; i++) {
				this.addImage(new MAImage(obj._embedded.images[i]));
			}
			for (var i=0; i < obj._embedded.effects.length; i++) {
				this.addEffect(MANote.fromJSON(obj._embedded.effects[i]));	
			}
			for (var i=0; i < obj._embedded.preventions.length; i++) {
				this.addPrevention(MANote.fromJSON(obj._embedded.preventions[i]));	
			}
			for (var i=0; i < obj._embedded.suggestions.length; i++) {
				this.addSuggestion(MANote.fromJSON(obj._embedded.suggestions[i]));	
				}
		}
		this.links = new MALinks(obj._links);
	}

	toJSON(): {}{
		return {
			id: this.id,
			state: this.state,
			who: this.who ? this.who.id : null,
			when: this.when ? this.when.toString() : null,
			images: this.images,
			effects: this.effects,
			preventions: this.preventions,
			suggestions: this.suggestions,
		};
	}

	get name():string {
		return this.influence.name;
	}

	addImage(obj:MAImage) {
		obj.source = this;
		this.images.push(obj);
	}
	addEffect(obj:MANote) {
		obj.setSource(this);
		this.effects.push(obj);
	}
	addPrevention(obj:MANote) {
		obj.setSource(this);
		this.preventions.push(obj);
	}
	addSuggestion(obj:MANote) {
		obj.setSource(this);
		this.suggestions.push(obj);
	}
	removeNote(obj:MANote) {
		var index;
		if ((index = this.effects.indexOf(obj)) >= 0) {
			var res = this.effects.splice(index, 1);
		}
		else if ((index = this.suggestions.indexOf(obj)) >= 0) {
			var res = this.suggestions.splice(index, 1);
		}
		else if ((index = this.preventions.indexOf(obj)) >= 0) {
			var res = this.preventions.splice(index, 1);
		}
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	id: number;
	influence: MAHintInfluence;
	user: MAUser;
	who: MAUser;
	when: Date; 
	images: MAImage[];
	effect: string;
	prevention: string;
	effects: MANote[];
	preventions: MANote[];
	suggestions: MANote[];
	comments: MACollection;
	commentCount: number;
	created: Date;
	links: MALinks;
	state: number = MASimulation.STATE_NOT_NECESSARY;
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
		if (obj.id) {
			this.id = obj.id;
			this.text = obj.text;
			this.class = obj.class;
			this.commentCount = obj.commentCount;
			this.user = new MAUser(obj._embedded.owner);
			this.created = new Date(obj.created.date);
		}
		this.links = new MALinks(obj._links);
	}

	get name():string {
		return this.source.name;
	}

	get description():string {
		return this.text;
	}

	setSource(obj:any) {
		this.source = obj;
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
	class: string;
	user: MAUser;
	source: any;
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
		this.children.items.unshift(child);
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
