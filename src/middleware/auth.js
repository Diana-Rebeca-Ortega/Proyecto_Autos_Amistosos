module.exports = (req, res, next) => {
    // Verificamos si existe la sesión del usuario
    if (req.session && req.session.usuario) {
        // Si hay sesión, el usuario puede continuar a la ruta original
        return next();
    } else {
        // Si no hay sesión, lo enviamos al login
        req.session.errorMessage = "Debes iniciar sesión para acceder.";
        return res.redirect('/login');
    }
};