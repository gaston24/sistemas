document.getElementById('buscar').addEventListener('click', async () => {
    const documento = document.getElementById('documento').value;
  
    if (!documento) {
      alert('Por favor, ingresa un documento.');
      return;
    }
  
    try {
      const response = await axios.post('http://localhost:3000/buscar-socio', {
        documento: documento,
      });
  
      const data = response.data;
      displayResult(data);
    } catch (error) {
      console.error('Error al buscar el socio:', error);
      alert('Hubo un error al buscar el socio. Por favor, intenta de nuevo.');
    }
  });
  
  function displayResult(data) {
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `
      <h3>Información del Socio</h3>
      <p><strong>Nombre:</strong> ${data.nombre}</p>
      <p><strong>Apellido:</strong> ${data.apellido}</p>
      <p><strong>Documento:</strong> ${data.documento}</p>
      <p><strong>Email:</strong> ${data.email}</p>
    `;
  }
  