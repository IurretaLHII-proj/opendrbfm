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
        this.id = obj.id;
        this.name = obj.name;
        this.links = new MALinks(obj._links);
    }
    return MAUser;
}());
var MAOperation = /** @class */ (function () {
    function MAOperation(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.description = obj.description;
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
    }
    return MAOperation;
}());
var MAHintType = /** @class */ (function () {
    function MAHintType(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.priority = obj.priority;
        this.user = new MAUser(obj._embedded.owner);
        this.operation = new MAOperation(obj._embedded.operation);
        this.description = obj.description;
        this.links = new MALinks(obj._links);
    }
    MAHintType.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
        };
    };
    return MAHintType;
}());
var MAHint = /** @class */ (function () {
    function MAHint() {
        this.priority = 0;
        this.comments = new MACollection();
        this.simulations = [];
        this.created = new Date;
    }
    MAHint.fromJSON = function (obj) {
        var e = new MAHint();
        e.load(obj);
        return e;
    };
    MAHint.prototype.load = function (obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.priority = obj.priority;
        this.type = new MAHintType(obj._embedded.type);
        this.user = new MAUser(obj._embedded.owner);
        this.description = obj.description;
        this.commentCount = obj.commentCount;
        this.links = new MALinks(obj._links);
        this.simulations = [];
        for (var i = 0; i < obj._embedded.simulations.length; i++) {
            this.addSimulation(MASimulation.fromJSON(obj._embedded.simulations[i]));
        }
    };
    MAHint.prototype.addSimulation = function (obj) {
        obj.setHint(this);
        this.simulations.push(obj);
    };
    MAHint.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHint.prototype.addComment = function (obj) {
        this.comments.items.push(obj);
    };
    MAHint.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            priority: this.priority,
            description: this.description,
            type: this.type ? this.type.id : 0,
            operation: this.type ? this.type.operation.id : 0,
        };
    };
    return MAHint;
}());
var MASimulation = /** @class */ (function () {
    function MASimulation() {
        this.state = MASimulation.NOT_PROCESSED;
        this.reasons = [];
        this.suggestions = [];
        this.influences = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MASimulation.fromJSON = function (obj) {
        var s = new MASimulation();
        s.load(obj);
        return s;
    };
    MASimulation.prototype.load = function (obj) {
        this.id = obj.id;
        this.state = obj.state;
        this.prevention = obj.prevention;
        this.effect = obj.effect;
        this.commentCount = obj.commentCount;
        this.when = obj.when ? new Date(obj.when.date) : null;
        this.who = obj.who;
        this.user = new MAUser(obj._embedded.owner);
        this.created = new Date(obj.created.date);
        this.reasons = [];
        this.suggestions = [];
        this.influences = [];
        for (var i = 0; i < obj._embedded.reasons.length; i++) {
            this.addReason(MANote.fromJSON(obj._embedded.reasons[i]));
        }
        for (var i = 0; i < obj._embedded.influences.length; i++) {
            this.addInfluence(MANote.fromJSON(obj._embedded.influences[i]));
        }
        for (var i = 0; i < obj._embedded.suggestions.length; i++) {
            this.addSuggestion(MANote.fromJSON(obj._embedded.suggestions[i]));
        }
        this.links = new MALinks(obj._links);
    };
    MASimulation.prototype.toJSON = function () {
        return {
            id: this.id,
            state: this.state,
            who: this.who,
            when: this.when ? this.when.toString() : null,
            prevention: this.prevention,
            effect: this.effect,
            reasons: this.reasons,
            influences: this.influences,
            suggestions: this.suggestions,
        };
    };
    MASimulation.prototype.setHint = function (obj) {
        this.hint = obj;
    };
    MASimulation.prototype.addReason = function (obj) {
        obj.setSimulation(this);
        this.reasons.push(obj);
    };
    MASimulation.prototype.addInfluence = function (obj) {
        obj.setSimulation(this);
        this.influences.push(obj);
    };
    MASimulation.prototype.addSuggestion = function (obj) {
        obj.setSimulation(this);
        this.suggestions.push(obj);
    };
    MASimulation.prototype.removeNote = function (obj) {
        var index;
        if ((index = this.reasons.indexOf(obj)) >= 0) {
            var res = this.reasons.splice(index, 1);
        }
        else if ((index = this.suggestions.indexOf(obj)) >= 0) {
            var res = this.suggestions.splice(index, 1);
        }
        else if ((index = this.influences.indexOf(obj)) >= 0) {
            var res = this.influences.splice(index, 1);
        }
        console.log(index, this, res);
    };
    MASimulation.prototype.getComments = function () {
        return this.comments.items;
    };
    MASimulation.prototype.addComment = function (obj) {
        this.comments.items.push(obj);
    };
    MASimulation.NOT_PROCESSED = 0;
    MASimulation.IN_PROGRESS = 1;
    MASimulation.FINISHED = 2;
    MASimulation.NOT_NECESSARY = -1;
    MASimulation.CANCELLED = -2;
    return MASimulation;
}());
var MANote = /** @class */ (function () {
    function MANote() {
        this.commentCount = 0;
        this.comments = new MACollection();
        this.created = new Date;
    }
    MANote.fromJSON = function (obj) {
        var s = new MANote();
        s.load(obj);
        return s;
    };
    MANote.prototype.load = function (obj) {
        this.id = obj.id;
        this.text = obj.text;
        this.commentCount = obj.commentCount;
        this.user = new MAUser(obj._embedded.owner);
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
    };
    MANote.prototype.setSimulation = function (obj) {
        this.simulation = obj;
    };
    MANote.prototype.getComments = function () {
        return this.comments.items;
    };
    MANote.prototype.addComment = function (obj) {
        this.comments.items.push(obj);
    };
    MANote.prototype.toJSON = function () {
        return {
            id: this.id,
            text: this.text,
        };
    };
    return MANote;
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