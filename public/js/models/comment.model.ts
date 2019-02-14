interface IMALinks {}
interface IMACollection {
	items: [],
	links: IMALinks,
}
interface IMADate {
	date: number, 
	timezone: string,
	timezone_type: number,
}
interface IMAUser {
	name: string,
	_links: IMALinks, 
}
interface IMAComment {
	id:number,
	body: string,
	commentCount: number, 
	created: IMADate ,
	_links: IMALinks, 
	_embedded: {
		owner: IMAUser,
	},
}

class MALinks {
	constructor(obj: IMALinks) {
		this.keys = obj;
	}

	getHref(name='self'):string {
		if (this.keys[name]) {
			return this.keys[name].href;
		}
	}

	keys:{}
}

class MAUser {

	constructor(obj: IMAUser) {
		this.name  = obj.name;
		this.links = new MALinks(obj._links);
	}

	name:string;
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
		this.children 		= [];
	}

	addChildren(children: MAComment[]) {
		for (var i = 0; i < children.length; i++) {
			this.addChild(children[i]);
		}
	}

	addChild(child: MAComment) {
		child.parent = this;
		this.children.push(child);
	}

	hasChildren(): boolean {
		return this.children.length > 0;
	}

	getChildren(): MAComment[] {
		return this.children;
	}

	id:number;
	body:string;
	commentCount:number;
	user: MAUser;
	parent: MAComment;
	children: MAComment[];
	created: Date;
	links: MALinks;
}
