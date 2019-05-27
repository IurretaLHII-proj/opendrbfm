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
