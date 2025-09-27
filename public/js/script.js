function editTransaksi(id) {
	window.location.href = `index.php?action=edit_transaksi&id=${id}`;
}
function deleteTransaksi(id) {
	if (confirm("Apakah Anda yakin ingin menghapus transaksi ini?")) {
		window.location.href = `index.php?action=delete_transaksi&id=${id}`;
	}
}

document.getElementById("kode_barang").addEventListener("change", function () {
	const selected = this.options[this.selectedIndex];
	document.getElementById("nama_barang").value = selected.getAttribute("data-nama") || "";
	document.getElementById("kategori").value = selected.getAttribute("data-kategori") || "";
});
