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

interface IMAImage {
	id:number,
	name:string;
	type:string;
	size:string;
	description:string;
	created: IMADate;
	_links: IMALinks, 
}

interface IMAAction {
	id:number,
	name:string;
	class:string;
	content:{};
	created: IMADate;
	_links: IMALinks; 
	_embedded: {
		process: IMAProcess,
		owner: IMAUser,
		source:any;
	},
}

interface IMAUser {
	id:number,
	name: string,
	roles: string[],
	_links: IMALinks, 
}

interface IMAMaterial {
	id:number,
	name: string,
	_links: IMALinks, 
}

interface IMAVersionType {
	id:number,
	name: string,
	description:string;
	_links: IMALinks, 
}

interface IMAProcess {
	id: number,
	title: string,
	body: string,
	number: number,
	code: number,
	line: number,
	machine: string,
	plant: string,
	complexity: string,
	pieceNumber: string,
	pieceName: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		customer: IMAUser,
		owner: IMAUser,
		versions: IMAVersion[],
	},
}

interface IMAVersion {
	id: number,
	name: string,
	description: string,
	state: number,
	commentCount: number,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		parent: IMAVersion,
		type: IMAVersionType,
		material: IMAMaterial,
		owner: IMAUser,
	},
}

interface IMAStage {
	id: number,
	order: number,
	body: string,
	commentCount: number,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		operations: IMAOperation[],
		images: IMAImage[],
		owner: IMAUser,
	},
}

interface IMANote {
	id:number,
	text: string,
	class:string;
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
	},
}

interface IMAOperationType {
	id: number,
	text: string,
	description: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		operations: IMAOperation[],
		owner: IMAUser,
	},
}

interface IMAOperation {
	id: number,
	name: string,
	longName: string,
	description: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		children: IMAOperation[],
		owner: IMAUser,
	},
}

interface IMAHintType {
	id: number,
	name: string,
	priority: number,
	description: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		operation: IMAOperation,
	},
}

interface IMAHintRelation {
	id: number,
	description: string,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		source: IMAHintReasonRel,
		relation: IMAHintInfluenceRel,
	},
}

interface IMAHintReasonRel {
	id: number,
	_links: IMALinks, 
	_embedded: {
		stage: IMAStage,
		hint:  IMAHint,
	},
}

interface IMAHintInfluenceRel {
	id: number,
	_links: IMALinks, 
	_embedded: {
		reason: IMAHintReasonRel,
	},
}

interface IMAHintReason {
	id: number,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		notes: IMANote[],
		relations: IMAHintRelation[],
		influences: IMAHintInfluence[],
	},
}

interface IMAHintInfluence {
	id: number,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		notes: IMANote[],
		relations: IMAHintRelation[],
		simulations: IMASimulation[],
	},
}

interface IMAHint {
	id: number,
	name: string,
	color: string,
	stageOrder: number,
	description: string,
	priority: number,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		type: IMAHintType,
		operation: IMAOperation,
		owner: IMAUser,
		reasons: IMAHintReason[],
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
		images: IMAImage[],
		effects: IMANote[],
		preventions: IMANote[],
		suggestions: IMANote[],
	},
	_links: IMALinks, 
}
