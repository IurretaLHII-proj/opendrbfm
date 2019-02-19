var MACollection = /** @class */ (function () {
    function MACollection() {
        this.loaded = false;
        this.items = [];
        this.links = new MALinks({});
        ;
    }
    MACollection.prototype.isEmpty = function () {
        return this.items.length == 0;
    };
    MACollection.prototype.has = function (name) {
        return this.links.has(name);
    };
    MACollection.prototype.load = function (obj) {
        this.page = obj.page;
        this.page_count = obj.page_count;
        this.page_size = obj.page_size;
        this.total_items = obj.total_items;
        this.links = new MALinks(obj._links);
        this.loaded = true;
        for (var i = 0; i < obj._embedded.items.length; i++) {
            this.items.push(obj._embedded.items[i]);
        }
    };
    return MACollection;
}());
var MALinks = /** @class */ (function () {
    function MALinks(obj) {
        this.keys = obj;
    }
    MALinks.prototype.getHref = function (name) {
        if (name === void 0) { name = 'self'; }
        if (this.keys[name]) {
            return this.keys[name].href;
        }
    };
    MALinks.prototype.has = function (name) {
        return typeof this.keys[name] !== "undefined";
    };
    return MALinks;
}());
var MAUser = /** @class */ (function () {
    function MAUser(obj) {
        this.name = obj.name;
        this.links = new MALinks(obj._links);
    }
    return MAUser;
}());
var MANote = /** @class */ (function () {
    function MANote() {
        this.comments = new MACollection();
    }
    MANote.prototype.getComments = function () {
        return this.comments.items;
    };
    MANote.prototype.addComment = function (obj) {
        this.comments.items.push(obj);
    };
    return MANote;
}());
var MAHint = /** @class */ (function () {
    function MAHint() {
        this.comments = new MACollection();
    }
    MAHint.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHint.prototype.addComment = function (obj) {
        this.comments.items.push(obj);
    };
    return MAHint;
}());
var MAComment = /** @class */ (function () {
    function MAComment(obj) {
        this.id = obj.id;
        this.body = obj.body;
        this.user = new MAUser(obj._embedded.owner);
        this.links = new MALinks(obj._links);
        this.created = new Date(obj.created.date);
        this.commentCount = obj.commentCount;
        this.children = new MACollection();
    }
    /*addChildren(children: MAComment[]) {
        for (var i = 0; i < children.length; i++) {
            this.addChild(children[i]);
        }
    }*/
    MAComment.prototype.addChild = function (child) {
        child.parent = this;
        this.children.items.push(child);
    };
    MAComment.prototype.hasChildren = function () {
        return !this.children.isEmpty();
    };
    MAComment.prototype.getChildren = function () {
        return this.children.items;
    };
    return MAComment;
}());
//# sourceMappingURL=comment.model.js.map