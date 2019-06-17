class EMAHint {
	static fromJSON(obj: IEMAHint): EMAHint{
		let e = new EMAHint();
		e.load(obj);
		return e;
	}

	constructor() {
		this.reasons  = [];
		this.created  = new Date;
	}

	load(obj: IEMAHint) {
		if (obj._embedded) {
			this.id = obj.id;
			this.name = obj.name;
			this.color = obj.color;
			this.priority = obj.priority;
			this.description = obj.description;
			this.created = new Date(obj.created.date);
			this.user  = new MAUser(obj._embedded.owner);
			this.stage = MAStage.fromJSON(obj._embedded.stage);
			this.version = MAVersion.fromJSON(obj._embedded.version);
			this.process = MAProcess.fromJSON(obj._embedded.process);
			this.operation = MAOperation.fromJSON(obj._embedded.operation);
			obj._embedded.reasons.forEach(e => {
				this.reasons.push(MAHintReason.fromJSON(e));	
			});
		}
		this.links = new MALinks(obj._links);
	}

	id: number;
	name: string;
	color: string;
	priority: number=0;
	description: string;
	user: MAUser;
	stage: MAStage;
	operation: MAOperation;
	version: MAVersion;
	process: MAProcess;
	reasons: MAHintReason[];
	created: Date;
	links: MALinks;
}

class EMAStage {
	static fromJSON(obj: IEMAStage): EMAStage{
		let e = new EMAStage();
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

	load(obj: IEMAStage) {
		if (obj.id) {
			this.id = obj.id;
			this.order = obj.order;
			this.body = obj.body;
			this.commentCount = obj.commentCount;
			this.created = new Date(obj.created.date);
			this.user = new MAUser(obj._embedded.owner);
			this.operations = [];
			this.images = [];
			this.process = MAProcess.fromJSON(obj._embedded.process);
			this.version = MAVersion.fromJSON(obj._embedded.version);
			obj._embedded.images.forEach(e => {
				this.images.push(new MAImage(e));	
			});
			obj._embedded.operations.forEach(e => {
				this.operations.push(MAOperation.fromJSON(e));	
			});
		}
		this.links = new MALinks(obj._links);
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

class EMAComment {

	constructor(obj: IEMAComment) {
		this.suscribers = [];
		this.comments 	= new MACollection();
		this.links 	  	= new MALinks(obj._links);
		this.created  	= new Date();
		if (obj.id) {
			this.id				= obj.id;
			this.class          = obj.class;
			this.body 			= obj.body;
			this.user 			= new MAUser(obj._embedded.owner);
			this.created 		= new Date(obj.created.date);
			this.commentCount 	= obj.commentCount;
			this.process		= MAProcess.fromJSON(obj._embedded.process); 
			switch (this.class) {
				case "MA\\Entity\\Comment\\Process":
					this.source = MAProcess.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\Version":
					this.source = MAVersion.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\Stage":
					this.source = MAStage.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\Hint":
					this.source = MAHint.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\HintReason":
					this.source = MAHintReason.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\HintInfluence":
					this.source = MAHintInfluence.fromJSON(obj._embedded.source);
					break;	
				case "MA\\Entity\\Comment\\Simulation":
					this.source = MASimulation.fromJSON(obj._embedded.source);
					break;	
				default:
					this.source = MANote.fromJSON(obj._embedded.source);
					break;	
			}
			obj._embedded.suscribers.forEach(e => {this.suscribers.push(new MAUser(e))});
			if (obj._embedded.parent) {
				this.parent		= new EMAComment(obj._embedded.parent); 
			}
		}
	}

	removeComment(child: MAComment) {
		var index = this.comments.items.indexOf(child);
		if (index != -1) {
			this.comments.items.splice(index, 1);
		}
	}

	hasParent(): boolean {
		return this.parent != null;
	}

	hasComments(): boolean {
		return !this.comments.isEmpty();
	}

	getComments():any[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		obj.parent = this;
		this.commentCount++;
		this.comments.items.unshift(obj);
	}

	toJSON():{} {
		return {
			id: this.id,
			body: this.body,
			suscribers: this.suscribers.map(e => {return e.id})
		};
	}

	id:number;
	class:string;
	body:string;
	source:any;
	commentCount:number=0;
	user: MAUser;
	suscribers: MAUser[];
	parent: EMAComment;
	comments: MACollection;
	process: MAProcess;
	created: Date;
	links: MALinks;
}
