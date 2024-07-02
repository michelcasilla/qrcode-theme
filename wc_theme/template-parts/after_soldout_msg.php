<style>
	.soldout.msg {
		position: relative;
		padding: 10px;
		color: white;
		background: orange;
		margin-top: 2rem;
	}

	.modal {
		display: none;
		position: fixed;
		z-index: 9999;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		overflow: auto;
		background-color: rgba(0, 0, 0, 0.5);
	}

	.modal-content {
		background-color: #fefefe;
		margin: 15% auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
		max-width: 600px;
	}

	.close {
		color: #aaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
		cursor: pointer;
		right: 14px;
		top: 6px;
		position: absolute;
	}

	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
</style>

<div class="soldout msg">
	<?= __("Este producto esta agotado, pero puede continuar con su orden y elegir otro disponible en oficina.", "sp") ?>
</div>

<div id="soldOutModal" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<h2>Info</h2>
		<p><?= __("Este producto esta agotado, pero puede continuar con su orden y elegir otro disponible en oficina.", "sp") ?></p>
		<button type="button" class="form-btn button-close"><?=__('Continuar', 'sp')?></button>
	</div>
</div>

<script>
	function openModalOnce() {
		var modal = document.getElementById("soldOutModal");
		var closeBtn = modal.getElementsByClassName("close")[0];
		var buttonClose = modal.getElementsByClassName("button-close")[0];
		const keyName = `modalShown_<?= the_ID() ?>`;
		// Check if the modal cookie exists
		if (document.cookie.indexOf(`${keyName}=true`) !== -1) {
			// Display the modal
			modal.style.display = "block";

			const closeModal = ()=>{
				modal.style.display = "none";
				// Set the modalShown cookie to indicate the modal has been shown
				document.cookie = `${keyName}=true; expires=Fri, 31 Dec 9999 23:59:59 GMT`;
			}
			// Close modal and set cookie when close button is clicked
			closeBtn.onclick = closeModal
			buttonClose.onclick = closeModal

			// Close modal and set cookie when user clicks outside the modal
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
					// Set the modalShown cookie to indicate the modal has been shown
					document.cookie = `${keyName}=true; expires=Fri, 31 Dec 9999 23:59:59 GMT`;
				}
			};
		}
	}
	// Call the function when the page finishes loading
	window.onload = openModalOnce;
</script>