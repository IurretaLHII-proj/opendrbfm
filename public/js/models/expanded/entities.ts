class EMAHint {
	static fromJSON(obj: IEMAHint): EMAHint{
		let e = new EMAHint();
		e.load(obj);
		return e;
	}

	constructor() {
	}

	load(obj: IEMAHint) {
		if (obj._embedded) {
			this.id = obj.id;
			this.name = obj.name;
			this.priority = obj.priority;
			this.description = obj.description;
			this.created = new Date(obj.created.date);
			this.user  = new MAUser(obj._embedded.owner);
			this.stage = MAStage.fromJSON(obj._embedded.stage);
			this.version = MAVersion.fromJSON(obj._embedded.version);
			this.process = MAProcess.fromJSON(obj._embedded.process);
		}
		this.links = new MALinks(obj._links);
	}

	id: number;
	name: string;
	priority: number=0;
	description: string;
	user: MAUser;
	stage: MAStage;
	version: MAVersion;
	process: MAProcess;
	created: Date;
	links: MALinks;
}
