Schema::create('stock_barang', function (Blueprint $table) {
    $table->id();
    $table->foreignId('id_barang')->constrained('barangs')->onDelete('cascade');
    $table->integer('jumlah')->default(0);
    $table->timestamps();
});