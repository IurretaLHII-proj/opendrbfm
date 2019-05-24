var EMAHint = /** @class */ (function () {
    function EMAHint() {
        this.priority = 0;
    }
    EMAHint.fromJSON = function (obj) {
        var e = new EMAHint();
        e.load(obj);
        return e;
    };
    EMAHint.prototype.load = function (obj) {
        if (obj._embedded) {
            this.id = obj.id;
            this.name = obj.name;
            this.priority = obj.priority;
            this.description = obj.description;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.stage = MAStage.fromJSON(obj._embedded.stage);
            this.version = MAVersion.fromJSON(obj._embedded.version);
            this.process = MAProcess.fromJSON(obj._embedded.process);
        }
        this.links = new MALinks(obj._links);
    };
    return EMAHint;
}());
//# sourceMappingURL=entities.js.map