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

	getHref(name='self'):string {
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
		this.name  = obj.name;
		this.links = new MALinks(obj._links);
	}

	name:string;
	links: MALinks;
}

class MANote {
	constructor() {
		this.comments = new MACollection();
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.comments.items.push(obj);
	}

	comments: MACollection;
	links: MALinks;
}

class MAHint {
	constructor() {
		this.comments = new MACollection();
	}

	getComments():MACollection[] {
		return this.comments.items;
	}

	addComment(obj:MAComment) {
		this.comments.items.push(obj);
	}

	name: string;
	comments: MACollection;
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
