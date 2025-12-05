<script>
    import * as L from "leaflet";
    import "leaflet/dist/leaflet.css";

    let { latitude, longitude, accuracy } = $props();

    function mapLifecycle(latitude, longitude, accuracy) {
        return (node) => {
            let map = L.map(node).setView([latitude, longitude], 16);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let sightingRange = L.circle([latitude, longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: accuracy
            }).addTo(map);

            // Teardown function
            return () => {
                map.remove();
            };
        };
    }
</script>

<div {@attach mapLifecycle(latitude, longitude, accuracy)}></div>

<style>
    div {
        height: 30rem;
    }
</style>