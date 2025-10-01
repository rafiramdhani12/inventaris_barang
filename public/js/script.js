function editTransaksi(id) {
	window.location.href = `index.php?action=edit_transaksi&id=${id}`;
}
function deleteTransaksi(id) {
	if (confirm("Apakah Anda yakin ingin menghapus transaksi ini?")) {
		window.location.href = `index.php?action=delete_transaksi&id=${id}`;
	}
}
