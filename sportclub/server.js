const express = require('express');
const axios = require('axios');
const cors = require('cors');
const path = require('path');
const helmet = require('helmet');  // Importar helmet

const app = express();
const port = 4000;

app.use(express.json());
app.use(cors());

// Configurar Helmet para agregar políticas de seguridad
// Configurar Helmet para agregar políticas de seguridad
app.use(
  helmet.contentSecurityPolicy({
    directives: {
      defaultSrc: ["'self'", "https:"],  // Permitir todo desde el mismo origen y HTTPS
      fontSrc: ["'self'", "https:", "data:"],  // Permitir fuentes desde el mismo origen, HTTPS y data URIs
      scriptSrc: ["'self'"],  // Permitir scripts desde el mismo origen
      styleSrc: ["'self'", "https:"],  // Permitir estilos desde el mismo origen y HTTPS
      imgSrc: ["'self'", "https:", "data:"],  // Permitir imágenes desde el mismo origen, HTTPS y data URIs
      connectSrc: ["'self'", "https:"],  // Permitir conexiones desde el mismo origen y HTTPS

    },
  })
);

app.use(cors({
  origin: 'http://app.xl.com.ar'
}));

app.post('/buscar-socio', async (req, res) => {
  const { documento } = req.body;

  try {
    const response = await axios({
      method: 'post',
      url: 'https://integraciones.prod.sportclub.com.ar/xl/socio/',
      headers: {
        'api-key': 'zOaLXtdje2xUQZUSeSItxF7KlHcEXYKPX0k4EDFpOM0yWy70B1LQCq4Ktyn7AZrj'
      },
      data: {
        documento: documento,
      },
    });
    console.log(response.data);
    res.json(response.data);
  } catch (error) {
    console.error('Error al buscar el socio:', error);
    res.status(500).json({ error: 'Error al buscar el socio' });
  }
});

app.listen(port,'0.0.0.0', () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});
