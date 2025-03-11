<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Itens Perdidos e Achados</title>
    <style>
        #map {
            height: 500px; /* Altura do mapa */
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Mapa de Itens Perdidos e Achados</h1>
    <div id="map"></div>

    <script>
        // Função para inicializar o mapa
        function initMap() {
            // Coordenadas do centro do mapa (pode ser a cidade ou um ponto central)
            const center = { lat: -20.4697105 , lng: -54.620121100000006 }; // Exemplo: São Paulo

            // Opções do mapa
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12, // Nível de zoom inicial
                center: center, // Centro do mapa
            });

            // Dados dos itens (substitua isso pelos dados do seu banco de dados)
            const items = @json($itens); // Passa os itens do backend para o JavaScript

            // Adiciona marcadores para cada item
            items.forEach(item => {
                if (!item.localizacao.latitude || !item.localizacao.longitude) {
                    return; // Pula itens sem localização válida
                }

                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(item.localizacao.latitude), 
                        lng: parseFloat(item.localizacao.longitude) 
                    },
                    map: map,
                    title: item.nome, // Título do marcador (pode ser o nome do item)
                });

                // InfoWindow para mostrar detalhes ao clicar no marcador
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <h3>${item.nome}</h3>
                        <p>${item.descricao}</p>
                        <p><strong>Local:</strong> ${item.localizacao.endereco}</p>
                    `,
                });

                // Abre o InfoWindow ao clicar no marcador
                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });
            });

            // Centraliza o mapa no primeiro item (opcional)
            if (items.length > 0 && items[0].localizacao.latitude && items[0].localizacao.longitude) {
                const firstItem = items[0];
                map.setCenter({
                    lat: parseFloat(firstItem.localizacao.latitude),
                    lng: parseFloat(firstItem.localizacao.longitude)
                });
            }
        }
    </script>

    <!-- Carrega a API do Google Maps -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"
        async
        defer
    ></script>
</body>
</html>

