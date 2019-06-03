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
    MACollection.prototype.removeElement = function (el) {
        if (this.items.splice(this.items.indexOf(el), 1)) {
            this.total_items--;
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
        if (this.has(name)) {
            return this.keys[name].href;
        }
    };
    MALinks.prototype.isAllowed = function (name) {
        if (this.has(name)) {
            return this.keys[name].allowed;
        }
        return false;
    };
    MALinks.prototype.has = function (name) {
        return typeof this.keys[name] !== "undefined";
    };
    return MALinks;
}());
var MAImage = /** @class */ (function () {
    function MAImage(obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.type = obj.type;
            this.size = obj.size;
            this.description = obj.description;
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    }
    MAImage.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            type: this.type,
            size: this.size,
            description: this.description,
            created: this.created,
        };
    };
    return MAImage;
}());
var MAAction = /** @class */ (function () {
    function MAAction() {
        this.created = new Date;
    }
    MAAction.fromJSON = function (obj) {
        var e = new MAAction();
        e.load(obj);
        return e;
    };
    MAAction.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.class = obj.class;
            this.content = obj.content;
            this.user = new MAUser(obj._embedded.owner);
            this.process = MAProcess.fromJSON(obj._embedded.process);
            this.created = new Date(obj.created.date);
            this.links = new MALinks(obj._links);
            switch (this.class) {
                case "MA\\Entity\\Action\\Note":
                    this.source = MANote.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\Process":
                    this.source = MAProcess.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\Version":
                    this.source = MAVersion.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\Stage":
                    this.source = MAStage.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\Hint":
                    this.source = MAHint.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\HintReason":
                    this.source = MAHintReason.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\HintInfluence":
                    this.source = MAHintInfluence.fromJSON(obj._embedded.source);
                    break;
                case "MA\\Entity\\Action\\Simulation":
                    this.source = MASimulation.fromJSON(obj._embedded.source);
                    break;
            }
        }
    };
    return MAAction;
}());
var MAUser = /** @class */ (function () {
    function MAUser(obj) {
        this.id = obj.id;
        this.name = obj.name;
        this.roles = obj.roles;
        this.links = new MALinks(obj._links);
    }
    return MAUser;
}());
var MAVersionType = /** @class */ (function () {
    function MAVersionType() {
        this.created = new Date();
    }
    MAVersionType.fromJSON = function (obj) {
        var e = new MAVersionType();
        e.load(obj);
        return e;
    };
    MAVersionType.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.description = obj.description;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    MAVersionType.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            description: this.description,
            user: this.user ? this.user.id : null,
        };
    };
    return MAVersionType;
}());
var MAMaterial = /** @class */ (function () {
    function MAMaterial() {
        this.priority = 0;
        this.created = new Date();
    }
    MAMaterial.fromJSON = function (obj) {
        var e = new MAMaterial();
        e.load(obj);
        return e;
    };
    MAMaterial.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.priority = obj.priority;
            this.description = obj.description;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    MAMaterial.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            priority: this.priority,
            description: this.description,
        };
    };
    return MAMaterial;
}());
var MAMachine = /** @class */ (function () {
    function MAMachine() {
        this.created = new Date();
    }
    MAMachine.fromJSON = function (obj) {
        var e = new MAMachine();
        e.load(obj);
        return e;
    };
    MAMachine.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.description = obj.description;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    MAMachine.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            description: this.description,
            user: this.user ? this.user.id : null,
        };
    };
    return MAMachine;
}());
var MAPlant = /** @class */ (function () {
    function MAPlant() {
        this.created = new Date();
    }
    MAPlant.fromJSON = function (obj) {
        var e = new MAPlant();
        e.load(obj);
        return e;
    };
    MAPlant.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.description = obj.description;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    MAPlant.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            description: this.description,
            user: this.user ? this.user.id : null,
        };
    };
    return MAPlant;
}());
var MAProcess = /** @class */ (function () {
    function MAProcess() {
        this.tpl = false;
        this.created = new Date;
        this.versions = [];
    }
    MAProcess.fromJSON = function (obj) {
        var e = new MAProcess();
        e.load(obj);
        return e;
    };
    MAProcess.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.title = obj.title;
            this.body = obj.body;
            this.number = obj.number;
            this.code = obj.code;
            this.tpl = obj.tpl;
            this.line = obj.line;
            this.complexity = obj.complexity;
            this.pieceNumber = obj.pieceNumber;
            this.pieceName = obj.pieceName;
            this.machine = MAMachine.fromJSON(obj._embedded.machine);
            this.plant = MAPlant.fromJSON(obj._embedded.plant);
            this.user = new MAUser(obj._embedded.owner);
            this.customer = new MAUser(obj._embedded.customer);
            this.created = new Date(obj.created.date);
            this.versions = [];
            for (var i = 0; i < obj._embedded.versions.length; i++) {
                this.addVersion(MAVersion.fromJSON(obj._embedded.versions[i]));
            }
            this.reloadVersions();
        }
        this.links = new MALinks(obj._links);
    };
    MAProcess.prototype.reloadVersions = function () {
        var _this = this;
        this.versions.forEach(function (version) { return version.children = []; });
        this.versions.forEach(function (version) {
            if (version.hasParent()) {
                _this.versions.find(function (e) { return e.id == version.parent.id; }).addChild(version);
            }
        });
    };
    MAProcess.prototype.toJSON = function () {
        return {
            id: this.id,
            title: this.title,
            body: this.body,
            number: this.number,
            code: this.code,
            line: this.line,
            tpl: this.tpl ? 1 : 0,
            plant: this.plant ? this.plant.id : null,
            machine: this.machine ? this.machine.id : null,
            complexity: this.complexity,
            pieceNumber: this.pieceNumber,
            pieceName: this.pieceName,
            user: this.user ? this.user.id : null,
            customer: this.customer ? this.customer.id : null,
        };
    };
    MAProcess.complexityLabel = function (value) {
        switch (value) {
            case MAProcess.COMPLEXITY_LOW: return "LOW";
            case MAProcess.COMPLEXITY_MEDIUM: return "MEDIUM";
            case MAProcess.COMPLEXITY_HIGH: return "HIGH";
            default: return "-";
        }
    };
    MAProcess.prototype.complexityLabel = function () {
        return MAProcess.complexityLabel(this.complexity);
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
    MAProcess.prototype.parentVersions = function () {
        return this.versions.filter(function (version) { return version.isParent(); });
    };
    MAProcess.prototype.getActive = function () {
        var parents = this.parentVersions();
        if (parents.length) {
            var last = parents[parents.length - 1];
            while (last.hasChildren()) {
                last = last.children[last.children.length - 1];
            }
            return last;
        }
    };
    MAProcess.prototype.isTpl = function () {
        return this.tpl;
    };
    MAProcess.COMPLEXITY_LOW = "AA";
    MAProcess.COMPLEXITY_MEDIUM = "BB";
    MAProcess.COMPLEXITY_HIGH = "A";
    return MAProcess;
}());
var MAVersion = /** @class */ (function () {
    function MAVersion() {
        this.commentCount = 0;
        this.state = MAVersion.STATE_IN_PROGRESS;
        this.stagesLoaded = false;
        this.stages = [];
        this.children = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MAVersion.stateLabel = function (value) {
        switch (value) {
            case MAVersion.STATE_IN_PROGRESS: return "In progress";
            case MAVersion.STATE_APPROVED: return "Approved";
            case MAVersion.STATE_CANCELLED: return "Cancelled";
            default: return "-";
        }
    };
    MAVersion.fromJSON = function (obj) {
        var e = new MAVersion();
        e.load(obj);
        return e;
    };
    MAVersion.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.name = obj.name;
            this.state = obj.state;
            this.description = obj.description;
            this.commentCount = obj.commentCount;
            this.created = new Date(obj.created.date);
            this.material = MAMaterial.fromJSON(obj._embedded.material);
            this.type = MAVersionType.fromJSON(obj._embedded.type);
            this.user = new MAUser(obj._embedded.owner);
            this.parent = obj._embedded.parent ? MAVersion.fromJSON(obj._embedded.parent) : null;
        }
        this.links = new MALinks(obj._links);
    };
    MAVersion.prototype.toJSON = function () {
        var stages = [];
        for (var i = 0; i < this.stages.length; i++) {
            stages.push(this.stages[i].toJSON());
        }
        return {
            id: this.id,
            name: this.name,
            state: this.state,
            description: this.description,
            material: this.material ? this.material.id : null,
            type: this.type ? this.type.id : null,
            parent: this.parent ? this.parent.id : null,
            stages: stages,
        };
    };
    MAVersion.prototype.stateLabel = function () {
        return MAVersion.stateLabel(this.state);
    };
    MAVersion.prototype.stateColor = function () {
        switch (this.state) {
            case MAVersion.STATE_APPROVED: return 'success';
            case MAVersion.STATE_CANCELLED: return 'danger';
            default: return 'dark';
        }
    };
    MAVersion.prototype.setProcess = function (obj) {
        this.process = obj;
    };
    MAVersion.prototype.isStagesLoaded = function () {
        return this.stagesLoaded;
    };
    MAVersion.prototype.addChild = function (version) {
        version.parent = this;
        this.children.push(version);
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
    MAVersion.prototype.hasParent = function () {
        return this.parent != null;
    };
    MAVersion.prototype.isParent = function () {
        return !this.hasParent();
    };
    MAVersion.prototype.hasChildren = function () {
        return this.children.length > 0;
    };
    MAVersion.prototype.hasStages = function () {
        return this.stages.length > 0;
    };
    MAVersion.prototype.isFirst = function (obj) {
        return this.stages.length && this.stages[0] === obj;
    };
    MAVersion.prototype.isLast = function (obj) {
        return this.stages.length && this.stages[this.stages.length - 1] === obj;
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
    MAVersion.STATE_IN_PROGRESS = 0;
    MAVersion.STATE_APPROVED = 1;
    MAVersion.STATE_CANCELLED = -1;
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
        if (obj.id) {
            this.id = obj.id;
            this.order = obj.order;
            this.body = obj.body;
            this.commentCount = obj.commentCount;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.operations = [];
            this.images = [];
            for (var i = 0; i < obj._embedded.images.length; i++) {
                this.addImage(new MAImage(obj._embedded.images[i]));
            }
            for (var i = 0; i < obj._embedded.operations.length; i++) {
                this.operations.push(MAOperation.fromJSON(obj._embedded.operations[i]));
            }
        }
        this.links = new MALinks(obj._links);
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
    Object.defineProperty(MAStage.prototype, "description", {
        get: function () {
            return this.body;
        },
        enumerable: true,
        configurable: true
    });
    MAStage.prototype.getHints = function () {
        return this.hints.sort(function (a, b) {
            if (a.priority < b.priority)
                return 1;
            else if (a.priority > b.priority)
                return -1;
            else
                return 0;
        });
    };
    MAStage.prototype.addHints = function (items) {
        for (var i = 0; i < items.length; i++) {
            this.addHint(items[i]);
        }
    };
    MAStage.prototype.addHint = function (obj) {
        obj.stage = this;
        this.hints.push(obj);
    };
    MAStage.prototype.addImage = function (obj) {
        obj.source = this;
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
        if (obj.id) {
            this.id = obj.id;
            this.text = obj.text;
            this.name = obj.text;
            this.description = obj.description;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
            this.operations = [];
            for (var i = 0; i < obj._embedded.operations.length; i++) {
                this.addOperation(MAOperation.fromJSON(obj._embedded.operations[i]));
            }
        }
        this.links = new MALinks(obj._links);
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
    MAOperationType.prototype.removeOperation = function (obj) {
        obj.type = null;
        this.operations.splice(this.operations.indexOf(obj), 1);
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
        if (obj._embedded) {
            this.id = obj.id;
            this.name = obj.name;
            this.color = obj.color;
            this.priority = obj.priority;
            this.user = new MAUser(obj._embedded.owner);
            this.operation = MAOperation.fromJSON(obj._embedded.operation);
            this.description = obj.description;
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    MAHintType.prototype.toJSON = function () {
        return {
            id: this.id,
            priority: this.priority,
            name: this.name,
            color: this.color,
            description: this.description,
        };
    };
    return MAHintType;
}());
var MAHintRelation = /** @class */ (function () {
    function MAHintRelation() {
        this.commentCount = 0;
        this.comments = new MACollection();
    }
    MAHintRelation.fromJSON = function (obj) {
        var e = new MAHintRelation();
        e.load(obj);
        return e;
    };
    MAHintRelation.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.description = obj.description;
            this.commentCount = obj.commentCount;
            this.user = new MAUser(obj._embedded.owner);
            this.source = MAHintReasonRel.fromJSON(obj._embedded.source);
            this.relation = MAHintInfluenceRel.fromJSON(obj._embedded.relation);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    Object.defineProperty(MAHintRelation.prototype, "name", {
        get: function () {
            if (this.reason)
                return this.reason.name;
            else if (this.influence)
                return this.influence.name;
        },
        enumerable: true,
        configurable: true
    });
    MAHintRelation.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHintRelation.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MAHintRelation.prototype.toJSON = function () {
        return {
            id: this.id,
            description: this.description,
            source: this.source ? this.source.toJSON() : {},
            relation: this.relation ? this.relation.toJSON() : {},
        };
    };
    return MAHintRelation;
}());
var MAHintReasonRel = /** @class */ (function () {
    function MAHintReasonRel() {
    }
    MAHintReasonRel.fromJSON = function (obj) {
        var e = new MAHintReasonRel();
        e.load(obj);
        return e;
    };
    MAHintReasonRel.prototype.setReason = function (obj) {
        this.id = obj.id;
        this.hint = obj.hint;
        this.stage = obj.hint.stage;
    };
    MAHintReasonRel.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.stage = MAStage.fromJSON(obj._embedded.stage);
            this.hint = MAHint.fromJSON(obj._embedded.hint);
        }
        this.links = new MALinks(obj._links);
    };
    MAHintReasonRel.prototype.toJSON = function () {
        return {
            id: this.id,
            hint: this.hint ? this.hint.id : null,
            stage: this.stage ? this.stage.id : null,
        };
    };
    return MAHintReasonRel;
}());
var MAHintInfluenceRel = /** @class */ (function () {
    function MAHintInfluenceRel() {
        this.reason = new MAHintReasonRel();
    }
    MAHintInfluenceRel.fromJSON = function (obj) {
        var e = new MAHintInfluenceRel();
        e.load(obj);
        return e;
    };
    MAHintInfluenceRel.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.reason = MAHintReasonRel.fromJSON(obj._embedded.reason);
        }
        this.links = new MALinks(obj._links);
    };
    Object.defineProperty(MAHintInfluenceRel.prototype, "hint", {
        get: function () {
            return this.reason.hint;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MAHintInfluenceRel.prototype, "stage", {
        get: function () {
            return this.reason.stage;
        },
        enumerable: true,
        configurable: true
    });
    MAHintInfluenceRel.prototype.toJSON = function () {
        return {
            id: this.id,
            reason: this.reason,
        };
    };
    return MAHintInfluenceRel;
}());
var MAHintReason = /** @class */ (function () {
    function MAHintReason() {
        this.commentCount = 0;
        this.notes = [];
        this.relations = [];
        this.influences = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MAHintReason.fromJSON = function (obj) {
        var e = new MAHintReason();
        e.load(obj);
        return e;
    };
    MAHintReason.prototype.load = function (obj) {
        if (obj._embedded) {
            this.id = obj.id;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.commentCount = obj.commentCount;
            this.notes = [];
            for (var i = 0; i < obj._embedded.notes.length; i++) {
                this.addNote(MANote.fromJSON(obj._embedded.notes[i]));
            }
            for (var i = 0; i < obj._embedded.influences.length; i++) {
                this.addInfluence(MAHintInfluence.fromJSON(obj._embedded.influences[i]));
            }
            for (var i = 0; i < obj._embedded.relations.length; i++) {
                if (obj._embedded.relations[i].id) {
                    this.addRelation(MAHintRelation.fromJSON(obj._embedded.relations[i]));
                }
            }
        }
        this.links = new MALinks(obj._links);
    };
    Object.defineProperty(MAHintReason.prototype, "name", {
        get: function () {
            return this.hint.name;
        },
        enumerable: true,
        configurable: true
    });
    MAHintReason.prototype.addNote = function (obj) {
        obj.setSource(this);
        this.notes.push(obj);
    };
    MAHintReason.prototype.removeNote = function (obj) {
        var index;
        if ((index = this.notes.indexOf(obj)) >= 0) {
            var res = this.notes.splice(index, 1);
        }
    };
    MAHintReason.prototype.addRelation = function (obj) {
        obj.reason = this;
        this.relations.push(obj);
    };
    MAHintReason.prototype.addInfluence = function (obj) {
        obj.setReason(this);
        this.influences.push(obj);
    };
    MAHintReason.prototype.getSimulations = function () {
        var items = [];
        for (var i = 0; i < this.influences.length; i++) {
            items = items.concat(this.influences[i].simulations);
        }
        return items;
    };
    MAHintReason.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHintReason.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MAHintReason.prototype.toJSON = function () {
        return {
            id: this.id,
            hint: this.hint ? this.hint.id : null,
            notes: this.notes,
            relations: this.relations,
            influences: this.influences,
        };
    };
    return MAHintReason;
}());
var MAHintInfluence = /** @class */ (function () {
    function MAHintInfluence() {
        this.commentCount = 0;
        this.notes = [];
        this.relations = [];
        this.simulations = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MAHintInfluence.fromJSON = function (obj) {
        var e = new MAHintInfluence();
        e.load(obj);
        return e;
    };
    MAHintInfluence.prototype.load = function (obj) {
        if (obj._embedded) {
            this.id = obj.id;
            this.created = new Date(obj.created.date);
            this.user = new MAUser(obj._embedded.owner);
            this.commentCount = obj.commentCount;
            this.notes = [];
            this.simulations = [];
            this.relations = [];
            for (var i = 0; i < obj._embedded.notes.length; i++) {
                this.addNote(MANote.fromJSON(obj._embedded.notes[i]));
            }
            for (var i = 0; i < obj._embedded.simulations.length; i++) {
                this.addSimulation(MASimulation.fromJSON(obj._embedded.simulations[i]));
            }
            for (var i = 0; i < obj._embedded.relations.length; i++) {
                if (obj._embedded.relations[i].id) {
                    this.addRelation(MAHintRelation.fromJSON(obj._embedded.relations[i]));
                }
            }
        }
        this.links = new MALinks(obj._links);
    };
    Object.defineProperty(MAHintInfluence.prototype, "name", {
        get: function () {
            return this.reason.name;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MAHintInfluence.prototype, "hint", {
        get: function () {
            return this.reason.hint;
        },
        enumerable: true,
        configurable: true
    });
    MAHintInfluence.prototype.setReason = function (obj) {
        this.reason = obj;
    };
    MAHintInfluence.prototype.addRelation = function (obj) {
        obj.influence = this;
        this.relations.push(obj);
    };
    MAHintInfluence.prototype.addNote = function (obj) {
        obj.setSource(this);
        this.notes.push(obj);
    };
    MAHintInfluence.prototype.removeNote = function (obj) {
        var index;
        if ((index = this.notes.indexOf(obj)) >= 0) {
            var res = this.notes.splice(index, 1);
        }
    };
    MAHintInfluence.prototype.addSimulation = function (obj) {
        obj.influence = this;
        this.simulations.push(obj);
    };
    MAHintInfluence.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHintInfluence.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MAHintInfluence.prototype.toJSON = function () {
        return {
            id: this.id,
            notes: this.notes,
            relations: this.relations,
            simulations: this.simulations,
        };
    };
    return MAHintInfluence;
}());
var MAHint = /** @class */ (function () {
    function MAHint() {
        this.priority = 0;
        this.comments = new MACollection();
        this.reasons = [];
        this.created = new Date;
    }
    MAHint.fromJSON = function (obj) {
        var e = new MAHint();
        e.load(obj);
        return e;
    };
    MAHint.prototype.load = function (obj) {
        if (obj._embedded) {
            this.id = obj.id;
            this.name = obj.name;
            this.color = obj.color;
            this.priority = obj.priority;
            this.type = MAHintType.fromJSON(obj._embedded.type);
            this.operation = MAOperation.fromJSON(obj._embedded.operation);
            this.user = new MAUser(obj._embedded.owner);
            this.description = obj.description;
            this.commentCount = obj.commentCount;
            this.stageOrder = obj.stageOrder;
            this.created = new Date(obj.created.date);
            for (var i = 0; i < obj._embedded.reasons.length; i++) {
                this.addReason(MAHintReason.fromJSON(obj._embedded.reasons[i]));
            }
        }
        this.links = new MALinks(obj._links);
    };
    MAHint.prototype.addReason = function (obj) {
        obj.hint = this;
        this.reasons.push(obj);
    };
    MAHint.prototype.getComments = function () {
        return this.comments.items;
    };
    MAHint.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MAHint.prototype.getInfluences = function () {
        var items = [];
        for (var i = 0; i < this.reasons.length; i++) {
            items = items.concat(this.reasons[i].influences);
        }
        return items;
    };
    MAHint.prototype.getSimulations = function () {
        var items = [];
        var infls = this.getInfluences();
        for (var i = 0; i < infls.length; i++) {
            items = items.concat(infls[i].simulations);
        }
        return items;
    };
    MAHint.prototype.toJSON = function () {
        return {
            id: this.id,
            name: this.name,
            color: this.color,
            stageOrder: this.stageOrder,
            priority: this.priority,
            description: this.description,
            type: this.type ? this.type.id : null,
            operation: this.operation ? this.operation.id : null,
        };
    };
    return MAHint;
}());
var MASimulation = /** @class */ (function () {
    function MASimulation() {
        this.who = null;
        this.state = MASimulation.STATE_NOT_NECESSARY;
        this.effects = [];
        this.suggestions = [];
        this.preventions = [];
        this.images = [];
        this.comments = new MACollection();
        this.created = new Date;
    }
    MASimulation.fromJSON = function (obj) {
        var s = new MASimulation();
        s.load(obj);
        return s;
    };
    MASimulation.stateLabel = function (value) {
        switch (value) {
            case MASimulation.STATE_NOT_NECESSARY: return "Not necessary";
            case MASimulation.STATE_NOT_PROCESSED: return "Not processed";
            case MASimulation.STATE_IN_PROGRESS: return "In progress";
            case MASimulation.STATE_FINISHED: return "Finished";
            case MASimulation.STATE_CANCELLED: return "Cancelled";
            default: return "-";
        }
    };
    MASimulation.prototype.stateLabel = function () {
        return MASimulation.stateLabel(this.state);
    };
    MASimulation.prototype.load = function (obj) {
        if (obj.id) {
            this.id = obj.id;
            this.state = obj.state;
            this.commentCount = obj.commentCount;
            this.when = obj.when ? new Date(obj.when.date) : null;
            this.who = obj._embedded.who ? new MAUser(obj._embedded.who) : null;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
            this.effects = [];
            this.suggestions = [];
            this.preventions = [];
            this.images = [];
            for (var i = 0; i < obj._embedded.images.length; i++) {
                this.addImage(new MAImage(obj._embedded.images[i]));
            }
            for (var i = 0; i < obj._embedded.effects.length; i++) {
                this.addEffect(MANote.fromJSON(obj._embedded.effects[i]));
            }
            for (var i = 0; i < obj._embedded.preventions.length; i++) {
                this.addPrevention(MANote.fromJSON(obj._embedded.preventions[i]));
            }
            for (var i = 0; i < obj._embedded.suggestions.length; i++) {
                this.addSuggestion(MANote.fromJSON(obj._embedded.suggestions[i]));
            }
        }
        this.links = new MALinks(obj._links);
    };
    MASimulation.prototype.toJSON = function () {
        return {
            id: this.id,
            state: this.state,
            who: this.who ? this.who.id : null,
            when: this.when ? this.when.toString() : null,
            images: this.images,
            effects: this.effects,
            preventions: this.preventions,
            suggestions: this.suggestions,
        };
    };
    Object.defineProperty(MASimulation.prototype, "name", {
        get: function () {
            return this.influence.name;
        },
        enumerable: true,
        configurable: true
    });
    MASimulation.prototype.addImage = function (obj) {
        obj.source = this;
        this.images.push(obj);
    };
    MASimulation.prototype.addEffect = function (obj) {
        obj.setSource(this);
        this.effects.push(obj);
    };
    MASimulation.prototype.addPrevention = function (obj) {
        obj.setSource(this);
        this.preventions.push(obj);
    };
    MASimulation.prototype.addSuggestion = function (obj) {
        obj.setSource(this);
        this.suggestions.push(obj);
    };
    MASimulation.prototype.removeNote = function (obj) {
        var index;
        if ((index = this.effects.indexOf(obj)) >= 0) {
            var res = this.effects.splice(index, 1);
        }
        else if ((index = this.suggestions.indexOf(obj)) >= 0) {
            var res = this.suggestions.splice(index, 1);
        }
        else if ((index = this.preventions.indexOf(obj)) >= 0) {
            var res = this.preventions.splice(index, 1);
        }
    };
    MASimulation.prototype.getComments = function () {
        return this.comments.items;
    };
    MASimulation.prototype.addComment = function (obj) {
        this.commentCount++;
        this.comments.items.unshift(obj);
    };
    MASimulation.STATE_NOT_PROCESSED = 0;
    MASimulation.STATE_IN_PROGRESS = 1;
    MASimulation.STATE_FINISHED = 2;
    MASimulation.STATE_NOT_NECESSARY = -1;
    MASimulation.STATE_CANCELLED = -2;
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
        if (obj.id) {
            this.id = obj.id;
            this.text = obj.text;
            this.class = obj.class;
            this.commentCount = obj.commentCount;
            this.user = new MAUser(obj._embedded.owner);
            this.created = new Date(obj.created.date);
        }
        this.links = new MALinks(obj._links);
    };
    Object.defineProperty(MANote.prototype, "name", {
        get: function () {
            return this.source.name;
        },
        enumerable: true,
        configurable: true
    });
    Object.defineProperty(MANote.prototype, "description", {
        get: function () {
            return this.text;
        },
        enumerable: true,
        configurable: true
    });
    MANote.prototype.setSource = function (obj) {
        this.source = obj;
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
        this.children.items.unshift(child);
    };
    MAComment.prototype.hasChildren = function () {
        return !this.children.isEmpty();
    };
    MAComment.prototype.getChildren = function () {
        return this.children.items;
    };
    return MAComment;
}());
//# sourceMappingURL=entities.js.map