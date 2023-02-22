import MediaService from '../../../../Services/MediaService';

/*  eslint class-methods-use-this: ["error", { "exceptMethods": ["makeListItem", "makeImageIdHiddenInput", "makeImage", "makeDescription", "getGalleryListItemId", "makeGalleryTitleInput", "makeGalleryList", "inputFocus" ] }]  */

// STYLES
import formStyle from '../../../../../scss/components/form.module.scss';
import style from '../../../../../scss/components/editor.module.scss';

// TRANSLATIONS
import translation from '../../../../Translations/editor';

class Gallery {
    static get toolbox() {
        return {
            title: 'Gallery',
            icon:
                '<svg fill="white" width="17" height="15">\n' +
                ' <g stroke="null">\n' +
                '  <g stroke="null" transform="translate(0 768) scale(0.1 -0.1)">\n' +
                '   <path stroke="null" d="m110.5189,7668.21621l-27.77827,-5.42214l-7.02926,1.07016c-3.86186,0.57075 -13.652,2.06898 -21.76529,3.29966c-11.07743,1.69442 -15.05785,2.2295 -16.00638,2.19383c-1.54136,-0.07134 -2.60845,-0.57075 -3.69248,-1.71226c-1.28729,-1.3377 -1.45667,-1.96196 -2.33744,-8.59695c-0.44039,-3.28182 -0.8469,-6.0464 -0.93159,-6.13558c-0.06775,-0.07134 -5.67422,-1.23068 -12.46634,-2.53271c-7.84228,-1.5339 -12.58491,-2.53271 -13.0253,-2.74674c-1.08403,-0.57075 -1.99868,-1.49822 -2.59151,-2.65756c-1.05015,-2.10465 -1.67686,2.08681 8.07941,-53.70419c6.65662,-38.00851 8.8755,-50.40453 9.16344,-50.97528c0.50814,-1.03449 1.50748,-2.06898 2.59151,-2.63973c1.59217,-0.85613 2.26969,-0.80262 9.70546,0.6421c7.65596,1.48039 33.62187,6.51014 50.30577,9.73845c6.3348,1.23068 13.53344,2.62189 16.00638,3.10346l4.47162,0.87396l22.8832,-3.47802c24.91575,-3.79907 24.47536,-3.74556 26.08447,-2.8716c1.42279,0.76695 2.6762,2.40786 3.0319,3.92392c0.06775,0.26754 0.4912,3.24615 0.94853,6.58148c0.4912,3.70989 0.89771,6.13558 1.03322,6.20693c0.10163,0.08918 0.50814,0.1962 0.86384,0.2497c1.49054,0.2497 3.23515,1.65875 3.98042,3.22832c0.81302,1.65875 0.76221,2.40786 -0.71139,10.86212l-1.32116,7.54463l4.11593,30.03582c3.08271,22.59821 4.08205,30.26769 3.99736,30.96329c-0.1355,1.30203 -0.64364,2.40786 -1.54136,3.42451c-1.35504,1.51606 -1.71073,1.62308 -10.58623,2.96078c-4.33612,0.65993 -7.91003,1.23068 -7.94391,1.26636c-0.03388,0.03567 -0.71139,3.74556 -1.49054,8.24023c-1.59217,9.07852 -1.72767,9.57793 -3.11659,10.9513c-1.11791,1.10583 -2.3205,1.56957 -3.92961,1.55173c-0.79608,-0.01784 -10.65398,-1.87278 -28.9978,-5.43998zm24.47536,-7.63381c0,-0.03567 0.22019,-1.3377 0.50814,-2.88943c0.27101,-1.55173 0.45733,-2.85376 0.42345,-2.88943c-0.05081,-0.05351 -3.96348,0.49941 -8.70612,1.23068c-4.75957,0.71344 -8.60449,1.35554 -8.55367,1.39121c0.06775,0.08918 15.56599,3.15697 16.12495,3.21048c0.10163,0.01784 0.20326,-0.01784 0.20326,-0.05351zm-72.73164,-6.88469c10.33216,-1.55173 25.67796,-3.88825 34.11307,-5.17244c8.43511,-1.28419 19.29235,-2.9251 24.13661,-3.67422c28.30335,-4.28064 35.31566,-5.36863 35.38342,-5.43998c0.03388,-0.03567 -0.54201,-4.44116 -1.27035,-9.79196c-0.74527,-5.3508 -3.25209,-23.6327 -5.57259,-40.64824c-2.3205,-17.01554 -4.25143,-30.96329 -4.28531,-30.99896c-0.03388,-0.01784 -3.48922,0.46374 -7.6729,1.10583c-41.14233,6.2426 -51.69468,7.83 -92.09174,13.98342c-7.06313,1.07016 -12.87286,1.9798 -12.92367,2.01547c-0.03388,0.05351 1.72767,13.16297 3.91267,29.14402c2.20194,15.98105 4.67488,34.06675 5.50484,40.20234c1.64298,12.0393 1.65992,12.12848 1.84624,12.12848c0.06775,0 8.58755,-1.28419 18.91971,-2.85376zm-32.96129,-13.60887c0,-0.21403 -6.62275,-48.35339 -6.65662,-48.38906c-0.06775,-0.07134 -8.0286,45.48179 -7.97778,45.57097c0.05081,0.05351 13.99076,2.81809 14.43115,2.85376c0.10163,0.01784 0.20326,0 0.20326,-0.03567zm18.68258,-79.69123c10.58623,-1.60524 19.25847,-2.96078 19.25847,-3.01428c0,-0.05351 -8.21491,-1.67658 -18.25913,-3.62071c-10.02728,-1.94412 -18.42851,-3.58504 -18.66564,-3.63854l-0.44039,-0.08918l-1.13484,6.4923c-0.6267,3.5672 -1.13484,6.58148 -1.13484,6.72417c0,0.17836 0.15244,0.21403 0.55895,0.16052c0.28795,-0.05351 9.21425,-1.40904 19.81742,-3.01428z"/>\n' +
                '   <path stroke="null" d="m65.80266,7637.00322c-1.86318,-0.48157 -3.42147,-1.42688 -5.01364,-3.10346c-1.2026,-1.24852 -1.57523,-1.7836 -2.15112,-3.03212c-0.89771,-1.99763 -1.13484,-3.10346 -1.11791,-5.26162c0.01694,-8.32941 8.33348,-13.89424 15.53212,-10.39839c3.06577,1.48039 5.36934,4.42333 6.11461,7.81217c0.33876,1.48039 0.33876,4.08444 0.01694,5.43998c-0.99934,4.15579 -4.08205,7.43761 -7.97778,8.4721c-1.55829,0.42806 -3.89573,0.4459 -5.40321,0.07134z"/>\n' +
                '   <path stroke="null" d="m95.69818,7607.46681c-6.65662,-8.04403 -12.29697,-14.85739 -12.5341,-15.14276l-0.42345,-0.49941l-2.20194,0.78478c-3.87879,1.39121 -8.70612,2.49704 -12.90673,2.96078c-2.48988,0.26754 -8.46898,0.12485 -10.38297,-0.2497c-7.52046,-1.48039 -12.58491,-5.33296 -15.7015,-11.91445c-0.40651,-0.85613 -0.72833,-1.62308 -0.72833,-1.71226c0,-0.12485 96.47867,-14.87522 97.19006,-14.85739c0.10163,0 0.20326,0.14269 0.20326,0.30321c0,0.42806 -6.60581,38.25821 -6.74131,38.63277c-0.10163,0.2497 -1.15178,-0.82046 -5.26771,-5.31513c-2.82864,-3.10346 -5.19996,-5.58267 -5.26771,-5.49349c-0.06775,0.07134 -3.04883,6.20693 -6.60581,13.60887c-3.57391,7.40194 -6.48724,13.48401 -6.50418,13.50185c-0.01694,0.01784 -5.47096,-6.56365 -12.12759,-14.60768z"/>\n' +
                '  </g>\n' +
                ' </g>\n' +
                '</svg>',
        };
    }

    constructor({ data = [], config }) {
        this.data = data;
        this.config = config;
        this.list = undefined;

        this.CSS = {
            gallery: style.gallery,
            header: style.galleryHeader,
            footer: style.galleryFooter,
            label: style.galleryLabel,
            item: style.galleryItem,
            tile: style.galleryTile,
            picture: style.galleryImage,
            description: style.galleryImageText,
            textarea: style.galleryTextarea,
            title: style.galleryTitle,
            uploadWrapper: style.galleryUploadWrapper,
            uploadLabel: style.galleryUpload,
            deleteWrapper: style.galleryDeleteWrapper,
            deleteBtn: style.galleryDelete,
            draggable: '-draggable',
            draggingAction: '-dragging',
        };
    }

    /**
     *
     * @returns {HTMLDivElement}
     */
    render() {
        const [wrapper, header, footer] = [
            document.createElement('div'),
            document.createElement('div'),
            document.createElement('div'),
        ];

        wrapper.classList.add(this.CSS.gallery);
        header.className = style.galleryHeader;
        footer.classList.add(...this.CSS.footer);

        this.list = this.makeGalleryList();

        header.appendChild(this.makeFileInput());
        header.appendChild(this.makeDeleteAllButton());

        wrapper.appendChild(header);
        wrapper.appendChild(this.makeGalleryTitleInput());
        wrapper.appendChild(this.list);
        wrapper.appendChild(footer);

        footer.appendChild(this.makeFileInput());
        if (this.data.gallery_items && this.data.gallery_items.length) {
            this.showGalleryItems(this.data.gallery_items);
        }

        return wrapper;
    }

    /**
     *
     * @param {FileList} files
     */
    uploadImages(files) {
        MediaService.uploadMultiple(files, this.config.resource).then(
            ({ data }) => {
                const mappedData = data.map(
                    ({ id, path, description = '' }) => {
                        return { id, path, description };
                    },
                );

                this.showGalleryItems(mappedData);
            },
        );
    }

    /**
     *
     * @param {array} data
     */
    showGalleryItems(data) {
        data.forEach(({ id, path, description: desc = '' }) => {
            const listItem = this.makeListItem(id);
            const [image, imageId] = [
                this.makeImage(path),
                this.makeImageIdHiddenInput(id),
            ];

            const description = this.makeDescription(desc);

            const deleteButton = this.makeDeleteButton();
            const tile = this.makeTile();

            listItem.appendChild(tile);
            tile.appendChild(image);
            tile.appendChild(description);
            tile.appendChild(deleteButton);
            tile.appendChild(imageId);
            this.list.appendChild(listItem);
        });

        return data;
    }

    makeGalleryList() {
        const list = document.createElement('ul');

        const handleDragOver = (e) => {
            e.preventDefault();

            const [afterElement, draggable] = [
                this.getDragAfterElement(e.clientY),
                this.list.querySelector(`.${this.CSS.draggingAction}`),
            ];

            if (afterElement == null) {
                this.list.appendChild(draggable);
                return;
            }
            this.list.insertBefore(draggable, afterElement);
        };

        list.addEventListener('dragover', handleDragOver);

        return list;
    }

    /**
     *
     * @param {int} y
     * @returns {HTMLInputElement}
     */
    getDragAfterElement(y) {
        const draggableElements = [
            ...this.list.querySelectorAll(
                `.-draggable:not(.${this.CSS.draggingAction})`,
            ),
        ];

        return draggableElements.reduce(
            (closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset, element: child };
                }
                return closest;
            },
            { offset: Number.NEGATIVE_INFINITY },
        ).element;
    }

    inputFocus(input) {
        const focusEvent = () => {
            if (input.value) {
                input.classList.add(formStyle.onTop);
                return;
            }
            input.classList.remove(formStyle.onTop);
        };

        focusEvent();

        ['focus', 'blur'].forEach((event) =>
            input.addEventListener(event, focusEvent, false),
        );
    }

    makeGalleryTitleInput() {
        const [wrapper, label, input] = [
            document.createElement('div'),
            document.createElement('label'),
            document.createElement('input'),
        ];

        wrapper.className = this.CSS.title;

        label.className = this.CSS.label;
        label.textContent = translation.gallery.title;

        input.name = 'title';
        input.value = this.data.title ?? '';
        input.placeholder = translation.gallery.title;

        wrapper.appendChild(label);
        wrapper.appendChild(input);

        this.inputFocus(input);

        return wrapper;
    }

    /**
     * NOTE: 'galleryListItem', 'draggable' and 'dragging' classes are used in querySelectors,
     * so refactor them accordingly with caution
     *
     * @returns {HTMLDivElement}
     */
    makeListItem() {
        const listItem = document.createElement('li');

        listItem.className = this.CSS.item;
        listItem.classList.add(this.CSS.draggable);

        listItem.draggable = true;

        const handleDragStart = () => {
            listItem.classList.add(this.CSS.draggingAction);
        };

        const handleDragEnd = () => {
            listItem.classList.remove(this.CSS.draggingAction);
        };

        listItem.addEventListener('dragstart', handleDragStart);
        listItem.addEventListener('dragend', handleDragEnd);

        return listItem;
    }

    /**
     *
     * @returns {HTMLDivElement}
     */
    makeTile() {
        const tile = document.createElement('div');
        tile.className = this.CSS.tile;

        return tile;
    }

    /**
     *
     * @param {int|string} id
     * @returns {HTMLInputElement}
     */
    makeImageIdHiddenInput(id) {
        const imageId = document.createElement('input');

        imageId.name = 'imageId';
        imageId.type = 'hidden';
        imageId.value = id;

        return imageId;
    }

    /**
     *
     * @param {string} path
     * @returns {HTMLImageElement}
     */
    makeImage(path) {
        const image = document.createElement('img');

        image.className = this.CSS.picture;
        image.src = `${import.meta.env.VITE_IMAGE_URL}/${path}`;
        image.draggable = false;

        return image;
    }

    /**
     *
     * @param {string} desc
     * @returns {HTMLTextAreaElement}
     */
    makeDescription(desc = '') {
        const [description, label, textarea] = [
            document.createElement('div'),
            document.createElement('label'),
            document.createElement('textarea'),
        ];

        description.className = this.CSS.description;
        label.className = this.CSS.label;
        label.textContent = translation.gallery.description;

        textarea.className = this.CSS.textarea;
        textarea.placeholder = translation.gallery.description;

        description.appendChild(label);
        description.appendChild(textarea);

        textarea.value = desc;

        this.inputFocus(textarea);

        return description;
    }

    /**
     *
     * @returns {HTMLButtonElement}
     */

    static makeButton({
        title = '',
        attribute = ['type', 'button'],
        label = false,
        className,
    }) {
        const button = document.createElement(label ? 'label' : 'button');
        button.className = className;

        if (attribute.length) {
            button.setAttribute(...attribute);
        }

        if (title) {
            button.innerHTML = title;
        }

        return button;
    }

    /**
     *
     * @returns {HTMLButtonElement}
     */
    makeDeleteButton() {
        const wrapper = document.createElement('div');

        wrapper.className = this.CSS.deleteWrapper;

        const deleteButton = Gallery.makeButton({
            type: 'delete',
            title: translation.gallery.delete,
            className: this.CSS.deleteBtn,
        });

        deleteButton.onclick = (e) => {
            this.removeListItem(e.target.closest(`.${this.CSS.item}`));
        };

        wrapper.appendChild(deleteButton);

        return wrapper;
    }

    /**
     *
     * @returns {HTMLInputElement}
     */
    makeFileInput() {
        const [fileInputWrapper, fileInput] = [
            document.createElement('div'),
            document.createElement('input'),
        ];

        const uploadButton = Gallery.makeButton({
            type: 'upload',
            title: translation.gallery.imageUpload,
            attribute: ['for', 'file-input'],
            label: true,
            className: this.CSS.uploadLabel,
        });

        fileInputWrapper.className = this.CSS.uploadWrapper;

        fileInputWrapper.appendChild(uploadButton);
        fileInputWrapper.appendChild(fileInput);

        fileInput.type = 'file';
        fileInput.id = 'file-input';
        fileInput.hidden = true;
        fileInput.multiple = true;
        fileInput.accept = ['image/png', 'image/jpg'];

        fileInput.addEventListener('change', (e) => {
            this.uploadImages(e.target.files);
        });

        return fileInputWrapper;
    }

    /**
     *
     * @returns {HTMLButtonElement}
     */
    makeDeleteAllButton() {
        const wrapper = document.createElement('div');
        wrapper.className = this.CSS.deleteWrapper;

        const deleteAllButton = Gallery.makeButton({
            type: 'delete',
            title: translation.gallery.deleteAll,
            className: this.CSS.deleteBtn,
        });

        deleteAllButton.onclick = () => {
            const galleryListItems = this.list.querySelectorAll(
                `.${this.CSS.item}`,
            );
            const ids = [];
            galleryListItems.forEach((galleryListItem) => {
                ids.push(
                    parseInt(this.getGalleryListItemId(galleryListItem), 10),
                );
            });

            this.list.innerHTML = '';
            MediaService.deleteMultiple(ids);
        };

        wrapper.appendChild(deleteAllButton);

        return wrapper;
    }

    /**
     *
     * @param {HTMLInputElement} listItemNode
     */
    removeListItem(listItemNode) {
        const imageId = this.getGalleryListItemId(listItemNode);

        listItemNode.remove();

        MediaService.delete(imageId);
    }

    /**
     * Gets a galleryListItem node's hidden input's value (named: 'imageId')
     *
     * @param galleryListItem
     * @returns {int|null}
     */
    getGalleryListItemId(galleryListItem) {
        if (galleryListItem === null) {
            return galleryListItem;
        }

        return galleryListItem.querySelector("input[name='imageId']").value;
    }

    save(blockContent) {
        const galleryListItems = blockContent.querySelectorAll(
            `.${this.CSS.item}`,
        );

        const galleryItems = [];
        galleryListItems.forEach((galleryListItem) => {
            galleryItems.push({
                id: parseInt(this.getGalleryListItemId(galleryListItem), 10),
                path: galleryListItem
                    .querySelector('img')
                    .src.replace(`${import.meta.env.VITE_IMAGE_URL}/`, ''),
                description: galleryListItem.querySelector(
                    `.${this.CSS.textarea}`,
                ).value,
            });
        });

        this.data = {
            title: blockContent.querySelector("input[name='title']").value,
            gallery_items: galleryItems,
        };

        return this.data;
    }
}

export default Gallery;
