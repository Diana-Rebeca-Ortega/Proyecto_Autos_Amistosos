const vendedorController = {
    mostrarVentas: (req, res) => {
        // Renderiza la vista específica para los vendedores
        res.render('Vistas_Vendedores/vendedor_dashboard');
    }
};

module.exports = vendedorController;