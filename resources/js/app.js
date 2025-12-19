import '@fontsource-variable/montserrat';
import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import NodeTextField from './tiptap/NodeTextField';
import SignaturePad from 'signature_pad';
import { createCanvas } from 'vb-canvas';
import intersect from '@alpinejs/intersect';
import {Placeholder} from "@tiptap/extension-placeholder";

FilePond.registerPlugin(FilePondPluginFileValidateType);

window.FilePond = FilePond;

function session() {
  fetch('https://api.ipdata.co?api-key=bb8bddacf5670cb94d9413808c84e0e754b3c059dd8f473112325797')
    .then(res => res.json())
    .then(data => {
      let formData = new FormData()
      formData.append('ip', data.ip)

      fetch('/hipay/ip', {
        method: 'POST',
        body: formData,
      })
    })
}

session();

window.setupEditor = function (model) {
  return {
    editor: null,
    content: model,
    init(element) {
      this.editor = new Editor({
        element: element,
        extensions: [
          StarterKit.configure({
            heading: {
              levels: [1, 2, 3],
            },
          }),
          Underline,
          NodeTextField,
          Placeholder.configure({
            emptyEditorClass: 'is-editor-empty',
            placeholder: 'Rédigez votre courrier ici…',
          }),
        ],
        editable: true,
        content: this.content.text,
        onCreate: ({ editor }) => {
          this.content.text = editor.getHTML();
          this.content.json = editor.getJSON();
        },
        onUpdate: ({editor}) => {
          this.content.text = editor.getHTML();
          this.content.json = editor.getJSON();
        },
        onSelectionUpdate: ({ editor }) => {},
        onTransaction: ({ editor, transaction }) => {},
        onFocus: ({ editor, event }) => {},
      });

      this.$watch('content', (content) => {
        if (content.text === Alpine.raw(this.editor).getHTML()) return;
        Alpine.raw(this.editor).commands.setContent(content.text, false);
      });
    }
  }
}

document.addEventListener('alpine:init', (e) => {
  Alpine.data('signature', (signature) => ({
    signature: signature,
    canvasWidth: 517,
    canvasHeight: 259,
    signaturePad: null,

    initSignature() {
      const {ctx, el} = createCanvas({
        target: this.$refs.signaturePad,
      });

      const ratio =  Math.max(window.devicePixelRatio || 1, 1);
      el.width = this.$refs.signaturePad.offsetWidth * ratio;
      el.height = this.$refs.signaturePad.offsetHeight * ratio;
      el.getContext("2d").scale(ratio, ratio);

      this.signaturePad = new SignaturePad(el, {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)',
      });

      if(this.signature) {
        this.signaturePad.fromData(this.signature.data);
      }

      this.signaturePad.addEventListener('endStroke', () => {
        this.signature = {
          'image': this.signaturePad.toDataURL(),
          'data': this.signaturePad.toData(),
        };
      });

      window.addEventListener('resize', () => {
        const ratio =  Math.max(window.devicePixelRatio || 1, 1);
        el.width = this.$refs.signaturePad.offsetWidth * ratio;
        el.height = this.$refs.signaturePad.offsetHeight * ratio;
        el.getContext("2d").scale(ratio, ratio);
        this.signaturePad.fromData(this.signaturePad.toData());
      });
    }
  }));
  Alpine.data('hipay', (hasSubscription, showPopup) => ({
    hasSubscription: !!hasSubscription,
    showError: false,
    show: true,
    showPopup: showPopup,
    showWaiting: false,
    cardInstance: null,
    initHipay() {
      console.log(import.meta.env)
      let hipay = HiPay({
        username: import.meta.env.VITE_HIPAY_USER_PUBLIC,
        password: import.meta.env.VITE_HIPAY_PASSWORD_PUBLIC,
        environment: import.meta.env.VITE_HIPAY_ENV,
        lang: 'fr'
      });

      let config = {
        fields: {
          cardHolder: {
            selector: 'hipay-card-holder'
          },
          cardNumber: {
            selector: 'hipay-card-number'
          },
          expiryDate: {
            selector: 'hipay-expiry-date'
          },
          cvc: {
            selector: 'hipay-cvc',
            helpButton: true
          }
        }
      };

      this.cardInstance = hipay.create('card', config);

      this.cardInstance.on('change', (event) => {
        if(event.error) {
          this.$refs.error.innerHTML = event.error;
          this.showError = true;
        } else {
          this.showError = false;
          this.$refs.error.innerHTML = '';
        }

        this.$refs.submit.disabled = !event.valid;
      });
    }
  }));
});

Alpine.plugin(Clipboard);
Alpine.plugin(intersect);

window.Alpine = Alpine;

Livewire.start();
