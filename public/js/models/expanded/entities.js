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
//# sourceMappingURL=entities.js.map