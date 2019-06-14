interface IEMAStage {
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
		process: IMAProcess,
		version: IMAVersion,
	},
}
interface IEMAHint {
	id: number,
	name: string,
	color: string,
	priority: number,
	description: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		process: IMAProcess,
		version: IMAVersion,
		stage: IMAStage,
		operation: IMAOperation,
		reasons: IMAHintReason[],
	},
}

interface IEMAComment {
	id:number,
	class:string;
	body: string,
	commentCount: number, 
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		suscribers: IMAUser[],
		parent: IEMAComment,
		process: IMAProcess,
		source: any,
	},
}
