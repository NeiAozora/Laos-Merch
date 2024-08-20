export class QuickConfirmModal {
  constructor(label, content, removeModalWhenClose = false) {
    // Generate a unique ID for the modal
    this.modalId = `quickConfirmModal-${Date.now()}`;
    this.removeModalWhenClose = removeModalWhenClose;
    this.modalHtml = `
      <div class="modal fade" id="${this.modalId}" tabindex="-1" aria-labelledby="${this.modalId}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="${this.modalId}Label">${label}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ${content}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
              <button type="button" class="btn btn-danger">Ya, batalkan</button>
            </div>
          </div>
        </div>
      </div>
    `;
    this.modal = null;
    this.yesButtonLabel = 'Ya, batalkan';
    this.noButtonLabel = 'Tidak';
    this.yesButtonColor = 'danger';
    this.noButtonColor = 'secondary';
    this.yesButtonDelegate = null;
    this.noButtonDelegate = null;
  }

  setYesButton(label, delegate, color = 'danger') {
    this.yesButtonLabel = label;
    this.yesButtonDelegate = delegate;
    this.yesButtonColor = color;
    return this;
  }

  setNoButton(label, delegate, color = 'secondary') {
    this.noButtonLabel = label;
    this.noButtonDelegate = delegate;
    this.noButtonColor = color;
    return this;
  }

  show() {
    // Create modal element and append to body
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = this.modalHtml;
    document.body.appendChild(modalContainer);

    this.modal = new bootstrap.Modal(document.getElementById(this.modalId));

    // Customize buttons
    const yesButton = document.querySelector(`#${this.modalId} .btn-danger`);
    const noButton = document.querySelector(`#${this.modalId} .btn-secondary`);

    yesButton.textContent = this.yesButtonLabel;
    yesButton.classList.remove('btn-danger');
    yesButton.classList.add(`btn-${this.yesButtonColor}`);
    yesButton.addEventListener('click', () => {
      if (this.yesButtonDelegate) this.yesButtonDelegate();
      this.remove();
    });

    noButton.textContent = this.noButtonLabel;
    noButton.classList.remove('btn-secondary');
    noButton.classList.add(`btn-${this.noButtonColor}`);
    noButton.addEventListener('click', () => {
      if (this.noButtonDelegate) this.noButtonDelegate();
      if (!this.removeModalWhenClose) {
        this.remove();
      } else {
        this.modal.hide();
      }
    });

    // Show the modal
    this.modal.show();
  }

  remove() {
    const modalElement = document.getElementById(this.modalId);
    if (modalElement) {
      this.modal.hide();
      modalElement.remove();
    }
  }
}
