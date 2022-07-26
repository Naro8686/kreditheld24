function render(proposal) {
    return {
        message: '',
        btnText: '',
        loading: false,
        currency: '€',
        allFilesName: [],
        otherCreditCount: 0,
        formData: proposal,
        dropFile: false,
        otherName: '',
        showHideComm: false,
        save: true,
        init() {
            this.otherCreditCount = this.formData.otherCredit.length || 0;
            this.allFilesName = proposal.uploads.map((name) => name.replace(/uploads\//gi, ''));
            if (!this.allFilesName.length) this.addFileField();
            this.showHideComm = this.showHideComment();
            this.addFileField(this.formData.uploads.length);
            // window.addEventListener('beforeunload', (e) => {
            //     if (!refreshKeyPressed && this.save && (this.formData.isPending || this.formData.draft)) {
            //         e.preventDefault();
            //         this.formData.draft = 1;
            //         return e.returnValue = this.submitData()?.then(function () {
            //             return '';
            //         });
            //     }
            // }, {capture: true});
        },
        exportToPdf() {
            this.save = false;
            let url = '/export-to-pdf';
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            let data = this.formData;
            Object.keys(data.insurance).map(function (key, index) {
                data.insurance[key] = +data.insurance[key];
            });
            $.post(url, data, () => location.href = url);
            return false;
        },
        getCategory() {
            try {
                let category_id = parseInt(this.formData.parent_category_id);
                let category = this.formData.parent_categories?.filter(function (item) {
                    return item.id === category_id;
                });
                return category.length ? category[0] : null;
            } catch (e) {
                console.error(e);
            }
            return null;
        },
        showHideComment() {
            let showHideComm = this.showHideComm;
            try {
                showHideComm = this.formData.categories[this.formData.parent_category_id]
                    .filter(category => category.id === parseInt(this.formData.category_id))[0].name === this.otherName;
            } catch (e) {
            }
            this.showHideComm = showHideComm;
            return showHideComm;

        },
        setOtherName(name) {
            this.otherName = name;
            return this;
        },
        documentsFilter() {
            let documents = [];
            let category_key = this.getCategory()?.category_key;
            let type = this.formData.applicantType;
            if (category_key === 'home') {
                if (type === 'individual') {
                    documents.push([
                        'Kopie der letzten 3 Gehaltsabrechnungen',
                        'Gültiger Deutscher Personalausweis/Reisepass (Reisepass nur in Verbindung mit einer Meldebescheinigung',
                        'Bei nicht EU-Bürger: Gültiger Reisepass mit aktuellem Aufenthaltstitel',
                        'Nachweis Eigenmittel/ Eigenkapital',
                        'Insofern bei Ihnen weitere Verpflichtungen (z.B. privat Kredite oder Immobilienfinanzierungen) bestehen, bitte Nachweis einreichen. (Kontoauszüge und Verträge)',
                        'Exposé, wenn keine vorhandenen Objektdaten im Portal eintragen.'
                    ]);
                } else if (type === 'juridical') {
                    documents.push([
                        'letzten 3 Einkommenssteuerbescheide +BWA laufendes Jahr',
                        'Gültiger Deutscher Personalausweis/Reisepass (Reisepass nur in Verbindung mit einer Meldebescheinigung',
                        'Bei nicht EU-Bürger: Gültiger Reisepass mit aktuellem Aufenthaltstitel',
                        'Nachweis Eigenmittel/ Eigenkapital',
                        'Insofern bei Ihnen weitere Verpflichtungen (z.B. privat Kredite oder Immobilienfinanzierungen) bestehen, bitte Nachweis einreichen. (Kontoauszüge und Verträge)',
                        'Exposé, wenn keine vorhandenen Objektdaten im Portal eintragen.'
                    ]);
                }
            } else if (category_key === 'private_credit') {
                if (type === 'individual') {
                    documents.push([
                        'Kopie der letzten 3 Gehaltsabrechnungen',
                        'Gültiger Deutscher Personalausweis/Reisepass(Reisepass nur in Verbindung mit einer Meldebescheinigung',
                        'Bei nicht EU-Bürger: Gültiger Reisepass mit aktuellem Aufenthaltstitel',
                        'Kontoauszüge: die letzten 5 Wochen',
                        'Insofern bei Ihnen weitere Verpflichtungen (z.B. Kredite) bestehen, bitte Nachweis einreichen. (Kontoauszüge und Verträge)',
                        'Bei verheirateten immer auch Unterlagen für Ehepartner miteinreichen'
                    ]);
                } else if (type === 'juridical') {
                    documents.push([
                        'letzten 3 Einkommenssteuerbescheide',
                        'Gültiger Deutscher Personalausweis/Reisepass(Reisepass nur in Verbindung mit einer Meldebescheinigung',
                        'Bei nicht EU-Bürger: Gültiger Reisepass mit aktuellem Aufenthaltstitel',
                        'Kontoauszüge: die letzten 5 Wochen',
                        'Insofern bei Ihnen weitere Verpflichtungen (z.B. Kredite) bestehen, bitte Nachweis einreichen. (Kontoauszüge und Verträge)',
                        'Bei verheirateten immer auch Unterlagen für Ehepartner miteinreichen'
                    ]);
                }
            }
            return documents.length ? documents[0] : documents;
        },
        limitNumberWithinRange(num, min, max) {
            const MIN = min || 0;
            const MAX = max || 4;
            const parsed = isNaN(parseInt(num)) ? min : parseInt(num);
            return Math.min(Math.max(parsed, MIN), MAX);
        },
        addFileField(key = 0) {
            return this.allFilesName[key] = '';
        },
        deleteFile(key = 0) {
            if (this.allFilesName[key] !== undefined) {
                this.allFilesName.splice(key, 1);
            }
            if (this.formData.uploads[key] !== undefined) {
                this.formData.uploads.splice(key, 1)
            }
        },
        uploadFile(file, key = 0) {
            try {
                this.allFilesName[key] = file.name;
                this.formData.uploads[key] = file;
                this.addFileField(this.formData.uploads.length);
                setTimeout(function () {
                    let el = document.querySelector(`[name='uploads[${key}]']`);
                    if (el) {
                        el.classList.add('upload');

                    }
                }, 500);
                return true;
            } catch (e) {
                console.error(e.message);
            }
            return false;
        },
        handleFileDrop(event, key) {
            if (event.dataTransfer.files.length > 0) {
                const files = event.dataTransfer.files;
                this.uploadFile(files[0], key);
                for (let i = 1; i < files.length; i++) {
                    key = this.allFilesName.length
                    this.addFileField(key);
                    this.uploadFile(files[i], key);
                }
            }
        },
        createdOtherCreditField(count) {
            this.otherCreditCount = this.limitNumberWithinRange(this.otherCreditCount, 0, 4);
            let otherCredit = this.formData.otherCredit;
            if (otherCredit.length !== this.otherCreditCount) {
                if (this.otherCreditCount <= 0) {
                    otherCredit = [];
                } else if (otherCredit.length < this.otherCreditCount) for (let iter = otherCredit.length; iter < this.otherCreditCount; iter++) {
                    otherCredit.push({monthlyPayment: '', creditBalance: '', repay: 'no', bankNumber: ''});
                }
                else if (otherCredit.length > this.otherCreditCount) {
                    otherCredit = otherCredit.slice(0, this.otherCreditCount);
                }
                this.formData.otherCredit = otherCredit;
            }
            return this.formData.otherCredit;
        },
        lessTwoYears() {
            try {
                if (this.formData.residenceDate) {
                    let date = new Date(this.formData.residenceDate);
                    let now = new Date();
                    let diff = (now.getTime() - date.getTime()) / 1000;
                    diff /= (60 * 60 * 24);
                    return (diff / 365.25) < 2;
                }
            } catch (e) {
                console.error(e);
            }
            return false
        },
        async sendNotice(e) {
            let textarea = $('textarea#notices-message');
            let message = $.trim(textarea.val());
            if (message.length) {
                const url = '/admin/proposal-notices';
                const data = {
                    message: message,
                    proposal_id: this.formData.id,
                };
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': document.head.querySelector('meta[name=csrf-token]').content,
                        }
                    });
                    const json = await response.json();
                    if (json.success) {
                        textarea.val('');
                        this.formData.notices = json.notices;
                    }
                } catch (error) {
                    console.error('Ошибка:', error);
                }
            }

        },
        submitData() {
            clearErrors();
            this.save = false;
            let is_draft = this.formData.draft;
            let form = document.forms.namedItem('proposal');
            let data = new FormData(form);
            data.append('draft', is_draft);
            this.formData.uploads.forEach(function (file, key) {
                if (typeof file === 'object') {
                    data.append(`uploads[${key}]`, file);
                }
            });
            this.allFilesName.forEach(function (fileName) {
                if (fileName) data.append('allFilesName[]', fileName);
            });
            this.loading = true;
            this.message = '';
            try {
                return request(form.action, form.method, data).then((data) => {
                    this.btnText = data.message;
                    if (data.hasOwnProperty("errors")) renderError(data.errors)
                    else if (data.success) {
                        if (!is_draft && data.hasOwnProperty("redirectUrl")) setTimeout(function () {
                            location.href = data.redirectUrl;
                        }, 1000);
                    } else throw 'error';
                }).catch((e) => {
                    throw e;
                }).finally(() => this.loading = false);
            } catch (error) {
                console.error(error);
                this.message = 'Whoops! Something went wrong.';
            }
            return null;
        }
    };
}

function clearErrors() {
    let classList = document.querySelectorAll(".border-red");
    [].forEach.call(classList, function (el) {
        el.classList.remove("border-red");
    });
    let elems = document.querySelectorAll("p.text-danger");
    [].forEach.call(elems, function (el) {
        el.remove();
    });
}

function renderError(data) {
    let form = document.forms.namedItem('proposal');
    for (let [name, errors] of Object.entries(data)) {
        let field = form.querySelector(`[name="${name}"]`);
        if (field === null) {
            let split = name.split('.');
            let iter = split[1] || 0;
            name = split[0];
            if (split.length > 2) {
                field = form.querySelector(`[name="${name}[${iter}][${split[2]}]`);
            } else {
                field = form.querySelector(`[name="${name}[${iter}]`);
            }
        }

        if (field) {
            let errElement = document.createElement("p");
            errElement.className = 'text-danger text-sm';
            errElement.textContent = errors[0];
            field.closest('div').appendChild(errElement);
            field.classList.add('border-red');
            $(field.closest('.collapse')).collapse('show');
        }
    }
}

async function request(url, method, data = {}) {
    const response = await fetch(url, {
        method: method, // *GET, POST, PUT, DELETE, etc.
        // cache: 'no-cache',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-Token': document.head.querySelector('meta[name=csrf-token]').content,
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: data
    });
    return await response.json();
}

var refreshKeyPressed = false;
var modifierPressed = false;

var f5key = 116;
var rkey = 82;
var modkey = [17, 224, 91, 93];

// Check for refresh keys
$(document).bind(
    'keydown',
    function (evt) {
        // Check for refresh
        if (evt.which == f5key || window.modifierPressed && evt.which == rkey) {
            refreshKeyPressed = true;
        }

        // Check for modifier
        if (modkey.indexOf(evt.which) >= 0) {
            modifierPressed = true;
        }
    }
);

// Check for refresh keys
$(document).bind(
    'keyup',
    function (evt) {
        // Check undo keys
        if (evt.which == f5key || evt.which == rkey) {
            refreshKeyPressed = false;
        }

        // Check for modifier
        if (modkey.indexOf(evt.which) >= 0) {
            modifierPressed = false;
        }
    }
);
