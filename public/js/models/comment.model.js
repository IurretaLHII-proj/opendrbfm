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
    return MALinks;
}());
var MAUser = /** @class */ (function () {
    function MAUser(obj) {
        this.name = obj.name;
        this.links = new MALinks(obj._links);
    }
    return MAUser;
}());
var MAComment = /** @class */ (function () {
    function MAComment(obj) {
        this.id = obj.id;
        this.body = obj.body;
        this.user = new MAUser(obj._embedded.owner);
        this.links = new MALinks(obj._links);
        this.created = new Date(obj.created.date);
        this.commentCount = obj.commentCount;
        this.children = [];
    }
    MAComment.prototype.addChildren = function (children) {
        for (var i = 0; i < children.length; i++) {
            this.addChild(children[i]);
        }
    };
    MAComment.prototype.addChild = function (child) {
        child.parent = this;
        this.children.push(child);
    };
    MAComment.prototype.hasChildren = function () {
        return this.children.length > 0;
    };
    MAComment.prototype.getChildren = function () {
        return this.children;
    };
    return MAComment;
}());
//# sourceMappingURL=comment.model.js.map