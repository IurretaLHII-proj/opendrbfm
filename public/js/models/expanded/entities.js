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
//# sourceMappingURL=entities.js.map