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
    MACollection.prototype.isLoaded = function () {
        return this.loaded;
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
var MAImage = /** @class */ (function () {
    function MAImage(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.type = obj.type;
        this.size = obj.size;
        this.description = obj.description;
        this.links = new MALinks(obj._links);
    }
    MAImage.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            type: this.type,
            size: this.size,
            description: this.description
        };
    };
    return MAImage;
}());
var MAUser = /** @class */ (function () {
    function MAUser(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.links = new MALinks(obj._links);
    }
    return MAUser;
}());
var MAMaterial = /** @class */ (function () {
    function MAMaterial(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.links = new MALinks(obj._links);
    }
    return MAMaterial;
}());
var MAProcess = /** @class */ (function () {
    function MAProcess() {
        this.created = new Date;
        this.versions = [];
    }
    MAProcess.fromJSON = function (obj) {
        var e = new MAProcess();
        e.load(obj);
        return e;
    };
    MAProcess.prototype.load = function (obj) {
        this.id = obj.id;
        this.title = obj.title;
        this.body = obj.body;
        this.number = obj.number;
        this.code = obj.code;
        this.line = obj.line;
        this.machine = obj.machine;
        this.plant = obj.plant;
        this.complexity = obj.complexity;
        this.pieceNumber = obj.pieceNumber;
        this.pieceName = obj.pieceName;
        this.user = new MAUser(obj._embedded.owner);
        this.customer = new MAUser(obj._embedded.customer);
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
        this.versions = [];
        for (var i = 0; i < obj._embedded.versions.length; i++) {
            this.addVersion(MAVersion.fromJSON(obj._embedded.versions[i]));
        }
    };
    MAProcess.prototype.toJSON = function () {
        return {
            id: this.id,
            title: this.title,
            body: this.body,
            number: this.number,
            code: this.code,
            line: this.line,
            machine: this.machine,
            plant: this.plant,
            complexity: this.complexity,
            pieceNumber: this.pieceNumber,
            pieceName: this.pieceNumber,
            user: this.user.id,
            customer: this.customer.id,
        };
    };
    MAProcess.prototype.hasVersions = function () {
        return this.versions.length > 0;
    };
    MAProcess.prototype.addVersion = function (obj) {
        obj.setProcess(this);
        this.versions.push(obj);
    };
    MAProcess.prototype.removeVersion = function (obj) {
        var i;
        if (-1 !== (i = this.versions.indexOf(obj))) {
            this.versions.splice(i, 1);
        }
    };
    MAProcess.prototype.getActive = function () {
        return this.versions[this.versions.length - 1];
    };
    MAProcess.COMPLEXITY_LOW = "AA";
    MAProcess.COMPLEXITY_MEDIUM = "BB";
    MAProcess.COMPLEXITY_HIGH = "A";
    return MAProcess;
}());
var MAVersion = /** @class */ (function () {
    function MAVersion() {
        this.commentCount = 0;
        this.stagesLoaded = false;
        this.stages = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MAVersion.fromJSON = function (obj) {
        var e = new MAVersion();
        e.load(obj);
        return e;
    };
    MAVersion.prototype.load = function (obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.description = obj.description;
        this.commentCount = obj.commentCount;
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
        this.material = new MAMaterial(obj._embedded.material);
        this.user = new MAUser(obj._embedded.owner);
    };
    MAVersion.prototype.toJSON = function () {
        var stages = [];
        for (var i = 0; i < this.stages.length; i++) {
            stages.push(this.stages[i].toJSON());
        }
        return {
            id: this.id,
            name: this.name,
            description: this.description,
            material: this.material ? this.material.id : null,
            stages: stages,
        };
    };
    MAVersion.prototype.setProcess = function (obj) {
        this.process = obj;
    };
    MAVersion.prototype.isStagesLoaded = function () {
        return this.stagesLoaded;
    };
    MAVersion.prototype.addStage = function (obj) {
        obj.version = this;
        this.stages.push(obj);
    };
    MAVersion.prototype.removeStage = function (obj) {
        var i;
        if (-1 !== (i = this.stages.indexOf(obj))) {
            this.stages.splice(i, 1);
        }
    };
    MAVersion.prototype.hasStages = function () {
        return this.stages.length > 0;
    };
    MAVersion.prototype.getActive = function () {
        return this.stages[this.stages.length - 1];
    };
    MAVersion.prototype.getComments = function () {
        return this.comments.items;
    };
    MAVersion.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    return MAVersion;
}());
var MAStage = /** @class */ (function () {
    function MAStage() {
        this.order = 0;
        this.commentCount = 0;
        this.hintsLoaded = false;
        this.hints = [];
        this.operations = [];
        this.images = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MAStage.fromJSON = function (obj) {
        var e = new MAStage();
        e.load(obj);
        return e;
    };
    MAStage.prototype.load = function (obj) {
        this.id = obj.id;
        this.order = obj.order;
        this.body = obj.body;
        this.commentCount = obj.commentCount;
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
        this.user = new MAUser(obj._embedded.owner);
        this.operations = [];
        this.images = [];
        for (var i = 0; i < obj._embedded.images.length; i++) {
            this.addImage(new MAImage(obj._embedded.images[i]));
        }
        for (var i = 0; i < obj._embedded.operations.length; i++) {
            this.operations.push(MAOperation.fromJSON(obj._embedded.operations[i]));
        }
    };
    MAStage.prototype.toJSON = function () {
        var operations = [];
        for (var i = 0; i < this.operations.length; i++) {
            operations[i] = { id: this.operations[i].id };
        }
        return {
            id: this.id,
            order: this.order,
            name: this.name,
            body: this.body,
            images: this.images,
            operations: operations,
            user: this.user ? this.user.id : null,
        };
    };
    MAStage.prototype.setProcess = function (obj) {
        this.process = obj;
    };
    Object.defineProperty(MAStage.prototype, "name", {
        get: function () {
            return "Stage " + this.order;
        },
        enumerable: true,
        configurable: true
    });
    MAStage.prototype.addHint = function (obj) {
        obj.stage = this;
        this.hints.push(obj);
    };
    MAStage.prototype.addImage = function (obj) {
        obj.stage = this;
        this.images.push(obj);
    };
    MAStage.prototype.isHintsLoaded = function () {
        return this.hintsLoaded;
    };
    MAStage.prototype.getComments = function () {
        return this.comments.items;
    };
    MAStage.prototype.addComment = function (obj) {
        obj.source = this;
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    return MAStage;
}());
var MAOperationType = /** @class */ (function () {
    function MAOperationType() {
        this.created = new Date;
        this.operations = [];
    }
    MAOperationType.fromJSON = function (obj) {
        var e = new MAOperationType;
        e.load(obj);
        return e;
    };
    MAOperationType.prototype.load = function (obj) {
        this.id = obj.id;
        this.text = obj.text;
        this.name = obj.text;
        this.description = obj.description;
        this.user = new MAUser(obj._embedded.owner);
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
        this.operations = [];
        for (var i = 0; i < obj._embedded.operations.length; i++) {
            this.addOperation(MAOperation.fromJSON(obj._embedded.operations[i]));
        }
    };
    MAOperationType.prototype.toJSON = function () {
        return {
            id: this.id,
            text: this.name,
            description: this.description,
        };
    };
    MAOperationType.prototype.addOperation = function (obj) {
        obj.type = this;
        this.operations.push(obj);
    };
    return MAOperationType;
}());
var MAOperation = /** @class */ (function () {
    function MAOperation() {
        this.type = null;
        this.hints = [];
        this.parents = [];
        this.children = [];
        this.created = new Date;
    }
    MAOperation.fromJSON = function (obj) {
        var e = new MAOperation();
        e.load(obj);
        return e;
    };
    MAOperation.prototype.load = function (obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.longName = obj.longName;
        this.description = obj.description;
        this.links = new MALinks(obj._links);
        this.children = [];
        if (obj._embedded) {
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            for (var i = 0; i < obj._embedded.children.length; i++) {
                this.addChild(MAOperation.fromJSON(obj._embedded.children[i]));
            }
        }
    };
    MAOperation.prototype.toJSON = function () {
        var children = [];
        for (var i = 0; i < this.children.length; i++) {
            children.push(this.children[i].toJSON());
        }
        return {
            id: this.id,
            name: this.name,
            description: this.description,
            children: children,
        };
    };
    MAOperation.prototype.addHint = function (obj) {
        obj.operation = this;
        this.hints.push(obj);
    };
    MAOperation.prototype.addChild = function (obj) {
        obj.parents.push(this);
        this.children.push(obj);
    };
    return MAOperation;
}());
var MAHintType = /** @class */ (function () {
    function MAHintType() {
        this.priority = 0;
    }
    MAHintType.fromJSON = function (obj) {
        var e = new MAHintType();
        e.load(obj);
        return e;
    };
    MAHintType.prototype.load = function (obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.priority = obj.priority;
        this.user = new MAUser(obj._embedded.owner);
        this.operation = MAOperation.fromJSON(obj._embedded.operation);
        this.description = obj.description;
        this.created = new Date(obj.created.date);
        this.links = new MALinks(obj._links);
    };
    MAHintType.prototype.toJSON = function () {
        return {
            id: this.id,
            priority: this.priority,
            name: this.name,
            description: this.description,
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
        this.type = MAHintType.fromJSON(obj._embedded.type);
        this.user = new MAUser(obj._embedded.owner);
        this.description = obj.description;
        this.commentCount = obj.commentCount;
        this.created = new Date(obj.created.date);
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
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MAHint.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            priority: this.priority,
            description: this.description,
            type: this.type ? this.type.id : null,
            operation: this.type ? this.type.operation.id : null,
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
    Object.defineProperty(MASimulation.prototype, "name", {
        get: function () {
            return this.hint.name + ' | Simulation';
        },
        enumerable: true,
        configurable: true
    });
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
        this.commentCount++;
        this.comments.items.unshift(obj);
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
    Object.defineProperty(MANote.prototype, "name", {
        get: function () {
            return this.simulation.name + ' | Note';
        },
        enumerable: true,
        configurable: true
    });
    MANote.prototype.setSimulation = function (obj) {
        this.simulation = obj;
    };
    MANote.prototype.getComments = function () {
        return this.comments.items;
    };
    MANote.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
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
        this.commentCount = 0;
        this.id = obj.id;
        this.body = obj.body;
        this.user = new MAUser(obj._embedded.owner);
        this.links = new MALinks(obj._links);
        this.created = new Date(obj.created.date);
        this.commentCount = obj.commentCount;
        this.children = new MACollection();
    }
    MAComment.prototype.toJSON = function () {
        return {
            id: this.id,
            body: this.body,
        };
    };
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
//# sourceMappingURL=entities.model.js.map