var EMAHint = /** @class */ (function () {
    function EMAHint() {
        this.priority = 0;
        this.reasons = [];
        this.created = new Date;
    }
    EMAHint.fromJSON = function (obj) {
        var e = new EMAHint();
        e.load(obj);
        return e;
    };
    EMAHint.prototype.load = function (obj) {
        var _this = this;
        if (obj._embedded) {
            this.id = obj.id;
            this.name = obj.name;
            this.color = obj.color;
            this.priority = obj.priority;
            this.description = obj.description;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.stage = MAStage.fromJSON(obj._embedded.stage);
            this.version = MAVersion.fromJSON(obj._embedded.version);
            this.process = MAProcess.fromJSON(obj._embedded.process);
            this.operation = MAOperation.fromJSON(obj._embedded.operation);
            obj._embedded.reasons.forEach(function (e) {
                _this.reasons.push(MAHintReason.fromJSON(e));
            });
        }
        this.links = new MALinks(obj._links);
    };
    return EMAHint;
}());
var EMAStage = /** @class */ (function () {
    function EMAStage() {
        this.order = 0;
        this.commentCount = 0;
        this.hintsLoaded = false;
        this.hints = [];
        this.operations = [];
        this.images = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    EMAStage.fromJSON = function (obj) {
        var e = new EMAStage();
        e.load(obj);
        return e;
    };
    EMAStage.prototype.load = function (obj) {
        var _this = this;
        if (obj.id) {
            this.id = obj.id;
            this.order = obj.order;
            this.body = obj.body;
            this.commentCount = obj.commentCount;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.operations = [];
            this.images = [];
            this.process = MAProcess.fromJSON(obj._embedded.process);
            this.version = MAVersion.fromJSON(obj._embedded.version);
            obj._embedded.images.forEach(function (e) {
                _this.images.push(new MAImage(e));
            });
            obj._embedded.operations.forEach(function (e) {
                _this.operations.push(MAOperation.fromJSON(e));
            });
        }
        this.links = new MALinks(obj._links);
    };
    return EMAStage;
}());
var EMAComment = /** @class */ (function () {
    function EMAComment(obj) {
        var _this = this;
        this.commentCount = 0;
        this.suscribers = [];
        this.comments = new MACollection();
        this.links = new MALinks(obj._links);
        this.created = new Date();
        if (obj.id) {
            this.id = obj.id;
            this.class = obj.class;
            this.body = obj.body;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
            this.commentCount = obj.commentCount;
            this.process = MAProcess.fromJSON(obj._embedded.process);
            switch (this.class) {
                case "MA\\Entity\\Comment\\Process":
                    this.source = MAProcess.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\Version":
                    this.source = MAVersion.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\Stage":
                    this.source = MAStage.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\Hint":
                    this.source = MAHint.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\HintReason":
                    this.source = MAHintReason.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\HintInfluence":
                    this.source = MAHintInfluence.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Comment\\Simulation":
                    this.source = MASimulation.fromJSON(obj._embedded.source);
                    break;
            }
            obj._embedded.suscribers.forEach(function (e) { _this.suscribers.push(new MAUser(e)); });
            if (obj._embedded.parent) {
                this.parent = new EMAComment(obj._embedded.parent);
            }
        }
    }
    EMAComment.prototype.removeComment = function (child) {
        var index = this.comments.items.indexOf(child);
        if (index != -1) {
            this.comments.items.splice(index, 1);
        }
    };
    EMAComment.prototype.hasComments = function () {
        return !this.comments.isEmpty();
    };
    EMAComment.prototype.getComments = function () {
        return this.comments.items;
    };
    EMAComment.prototype.addComment = function (obj) {
        obj.parent = this;
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    EMAComment.prototype.toJSON = function () {
        return {
            id: this.id,
            body: this.body,
            suscribers: this.suscribers.map(function (e) { return e.id; })
        };
    };
    return EMAComment;
}());
//# sourceMappingURL=entities.js.map