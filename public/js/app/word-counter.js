document.addEventListener('alpine:init', () => {
    document.querySelectorAll('[data-word-counter]').forEach((wrapper) => {
        let tries = 0;
        const interval = setInterval(() => {
            const cm = wrapper.querySelector('.CodeMirror');
            tries++;

            if (cm && cm.CodeMirror) {
                clearInterval(interval);
                const display = wrapper.querySelector('[data-word-count]');

                const updateCount = () => {
                    const text = cm.CodeMirror.getValue().trim();
                    const count = text === '' ? 0 : text.split(/\s+/).length;
                    if (display) display.innerText = count + ' mots';
                };

                cm.CodeMirror.on('change', updateCount);
                updateCount();
            } else if (tries > 50) {
                clearInterval(interval);
                console.warn('CodeMirror introuvable pour ce champ');
            }
        }, 100);
    });
});