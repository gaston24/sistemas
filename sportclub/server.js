const express = require('express');
const axios = require('axios');
const cors = require('cors');

const app = express();
const port = 3000;

app.use(express.json());
app.use(cors());

app.post('/buscar-socio', async (req, res) => {
  const { documento } = req.body;

  try {
    const response = await axios({
      method: 'post',
      url: 'https://integraciones.stg.sportclub.com.ar/cheeky/socio/',
      headers: {
        'api-key': 'X4UQgbx7RMIkvJjQozuBMRUWOUqfPIf086sg5L302OR7O3lgPZBgvk1hGMpRPHyi',
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

app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});
