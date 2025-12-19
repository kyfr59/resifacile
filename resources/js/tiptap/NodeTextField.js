import { Node } from '@tiptap/core';
import { mergeAttributes } from "@tiptap/vue-3";

export default Node.create({
    name: 'nodeTextField',

    group: 'inline',

    inline: true,

    content: 'text*',

    addAttributes() {
        return {
            id: {
                parseHTML: element => element.getAttribute('id'),
            },
            label: {
                parseHTML: element => element.getAttribute('label'),
            }
        }
    },

    parseHTML() {
        return [
            {
                tag: 'text',
            },
        ]
    },

    renderHTML({ HTMLAttributes }) {
        return ['text', mergeAttributes(HTMLAttributes)]
    },

    addNodeView() {
        return ({ editor, node, getPos, HTMLAttributes, decorations, extension }) => {
            const dom = document.createElement('div');
            const innerText = document.createElement('div');

            dom.id = HTMLAttributes.id;
            dom.classList.add('text__input');

            innerText.contentEditable = true;
            innerText.setAttribute('aria-placeholder', HTMLAttributes.label);
            innerText.addEventListener('blur', function (e) {
                if(e.target.innerHTML === '<br>') {
                    e.target.innerHTML = null;
                }
            });

            dom.appendChild(innerText);

            return {
                dom,
                contentDOM: innerText,
            }
        }
    },
});
