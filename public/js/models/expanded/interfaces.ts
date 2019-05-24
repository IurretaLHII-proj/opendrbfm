interface IEMAHint {
	id: number,
	name: string,
	priority: number,
	description: string,
	created: IMADate,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
		process: IMAProcess,
		version: IMAVersion,
		stage: IMAStage,
	},
}
