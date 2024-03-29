function i({ state: o }) {
    return {
        state: o,
        rows: [],
        shouldUpdateRows: !0,
        init: function () {
            this.updateRows(),
                this.rows.length <= 0 && this.addRow(),
                (this.shouldUpdateRows = !0),
                this.$watch("state", () => {
                    if (!this.shouldUpdateRows) {
                        this.shouldUpdateRows = !0;
                        return;
                    }
                    this.updateRows();
                });
        },
        addRow: function () {
            this.rows.push({ key: "", value: "" }), this.updateState();
        },
        deleteRow: function (t) {
            this.rows.splice(t, 1),
                this.rows.length <= 0 && this.addRow(),
                this.updateState(),
                (this.shouldUpdateRows = !0);
        },
        reorderRows: function (t) {
            let e = Alpine.raw(this.rows),
                s = e.splice(t.oldIndex, 1)[0];
            e.splice(t.newIndex, 0, s), (this.rows = e), this.updateState();
        },
        updateRows: function () {
            let t = [];
            for (let [e, s] of Object.entries(this.state ?? {}))
                t.push({ key: e, value: s });
            this.rows = t;
        },
        updateState: function () {
            let t = {};
            this.rows.forEach((e) => {
                e.key === "" || e.key === null || (t[e.key] = e.value);
            }),
                (this.shouldUpdateRows = !1),
                (this.state = t);
        },
    };
}
export { i as default };
