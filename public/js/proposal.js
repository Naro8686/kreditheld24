function render(proposal) {
    return {
        message: '',
        btnText: '',
        loading: false,
        currency: '€',
        allFilesName: [],
        otherCreditCount: 0,
        formData: proposal,
        init() {
            this.otherCreditCount = this.formData.otherCredit.length || 0;
            this.allFilesName = proposal.uploads.map((name) => name.replace(/uploads\//gi, ''));
            if (!this.allFilesName.length) this.addFileField();
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
        uploadFile(event, key = 0) {
            let el = event.target;
            if (el.files.length) {
                let file = el.files[0];
                this.allFilesName[key] = file.name;
                this.formData.uploads[key] = file;
                el.classList.add('upload');
                return true;
            }
            return false;
        },
        deleteFile(key = 0) {
            if (this.allFilesName[key] !== undefined) {
                this.allFilesName.splice(key, 1);
            }
            if (this.formData.uploads[key] !== undefined) {
                this.formData.uploads.splice(key, 1)
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
        submitData() {
            clearErrors();
            let form = document.forms.namedItem('proposal');
            let data = new FormData(form);
            this.allFilesName.forEach(function (fileName) {
                if (fileName) data.append('allFilesName[]', fileName);
            });
            this.loading = true;
            this.message = '';
            try {
                request(form.action, form.method, data).then((data) => {
                    this.btnText = data.message;
                    if (data.hasOwnProperty("errors")) renderError(data.errors)
                    else if (data.success) setTimeout(function () {
                        location.href = data.redirectUrl;
                    }, 1000);
                    else throw 'error';
                }).catch((e) => {
                    throw e;
                }).finally(() => this.loading = false);
            } catch (error) {
                console.error(error);
                this.message = 'Ooops! Something went wrong!';
            }
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
