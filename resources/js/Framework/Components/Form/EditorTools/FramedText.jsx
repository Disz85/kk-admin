class FramedText {
    static get isInline() {
        return true;
    }

    static get sanitize() {
        return {
            framed: {
                class: 'cdx-framed',
            },
        };
    }

    constructor({ api }) {
        this.api = api;
        this.button = null;
        this.state = false;
        this.tag = 'FRAMED';
        this.class = 'cdx-framed';
    }

    render() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.innerHTML = '<span>F</span>';
        this.button.classList.add(this.api.styles.inlineToolButton);

        return this.button;
    }

    surround(range) {
        if (this.state) {
            this.unwrap(range);
            return;
        }

        this.wrap(range);
    }

    wrap(range) {
        const selectedText = range.extractContents();

        const framed = document.createElement(this.tag);
        framed.classList.add(this.class);
        framed.appendChild(selectedText);

        range.insertNode(framed);

        this.api.selection.expandToTag(framed);
    }

    unwrap(range) {
        const framed = this.api.selection.findParentTag(this.tag);
        const text = range.extractContents();

        framed.remove();

        range.insertNode(text);
    }

    checkState() {
        const framed = this.api.selection.findParentTag(this.tag);

        this.state = !!framed;
    }
}

export default FramedText;
