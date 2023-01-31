import MediaService from '../../../../Services/MediaService';

class Image {
    static get toolbox() {
        return {
            title: 'Image',
            icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>',
        };
    }

    constructor({ data, config, api }) {
        this.api = api;
        this.data = {
            path: data.path || null,
            caption: data.caption || null,
            alt: data.alt || null,
            float: data.float !== undefined ? data.float : false,
        };
        this.config = config;
        this.settings = [
            {
                name: 'float',
                icon: `<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.5,8.6L17.5,8.6l-11.2,0l3.5-3.8C9.9,4.6,10,4.3,10,4S9.9,3.5,9.8,3.3L9.2,2.7C9,2.5,8.8,2.4,8.6,2.4 c-0.2,0-0.5,0.1-0.7,0.3L1.8,9.3C1.7,9.5,1.6,9.7,1.6,10c0,0.3,0.1,0.5,0.3,0.7l6.1,6.6c0.2,0.2,0.4,0.3,0.7,0.3 c0.2,0,0.5-0.1,0.7-0.3l0.6-0.6C9.9,16.5,10,16.2,10,16c0-0.3-0.1-0.5-0.3-0.7l-3.6-3.9h11.3c0.5,0,0.9-0.5,0.9-1V9.6 C18.4,9,18,8.6,17.5,8.6z"/></svg>`,
            },
        ];
        this.wrapper = undefined;
    }

    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('idx-image');

        const input = document.createElement('input');
        input.type = 'file';

        input.addEventListener('change', (e) => {
            this.uploadImage(e.target.files[0]);
        });

        this.wrapper.appendChild(input);

        if (this.data.path !== null) {
            this.showImage(this.data.path, this.data.caption, this.data.alt);
        }

        return this.wrapper;
    }

    renderSettings() {
        const wrapper = document.createElement('div');

        this.settings.forEach((setting) => {
            const button = document.createElement('div');
            button.classList.add('cdx-settings-button');
            button.classList.toggle(
                'cdx-settings-button--active',
                this.data[setting.name],
            );
            button.innerHTML = setting.icon;

            button.addEventListener('click', () => {
                this.toggleSetting(setting.name);
                button.classList.toggle('cdx-settings-button--active');
            });

            wrapper.appendChild(button);
        });

        return wrapper;
    }

    toggleSetting(setting) {
        this.data[setting] = !this.data[setting];
        this.acceptSettings();
    }

    acceptSettings() {
        this.settings.forEach((setting) => {
            this.wrapper.classList.toggle(
                `idx-image-setting-${setting.name}`,
                !!this.data[setting.name],
            );
        });
    }

    uploadImage(file) {
        MediaService.upload(file, this.config.resource).then(({ data }) => {
            this.showImage(data.path, '', '');
        });
    }

    showImage(path, captionValue, altValue) {
        const image = document.createElement('img');
        image.src = `${import.meta.env.VITE_IMAGE_URL}/${path}`;
        image.style.width = 'auto';
        image.style.display = 'block';
        image.style.marginBottom = '5px';
        image.style.maxWidth = '100%';

        const caption = document.createElement('input');
        caption.type = 'text';
        caption.value = captionValue;
        caption.placeholder = this.api.i18n.t('Caption');
        caption.setAttribute('data-role', 'caption');

        const alt = document.createElement('input');
        alt.type = 'text';
        alt.value = altValue;
        alt.placeholder = this.api.i18n.t('Alt');
        alt.setAttribute('data-role', 'alt');

        this.wrapper.innerHTML = '';
        this.wrapper.appendChild(image);
        this.wrapper.appendChild(caption);
        this.wrapper.appendChild(alt);

        this.acceptSettings();
    }

    save(blockContent) {
        const image = blockContent.querySelector('img');

        const captionInput = blockContent.querySelector(
            'input[data-role="caption"]',
        );

        const altInput = blockContent.querySelector('input[data-role="alt"]');

        if (image === null || captionInput === null || altInput === null) {
            return null;
        }

        return Object.assign(this.data, {
            path: image.src.replace(`${import.meta.env.VITE_IMAGE_URL}/`, ''),
            caption: captionInput.value === '' ? null : captionInput.value,
            alt: altInput.value === '' ? null : altInput.value,
        });
    }
}

export default Image;
