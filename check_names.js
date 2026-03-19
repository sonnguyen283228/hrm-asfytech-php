async function run() {
    const res = await fetch('https://provinces.open-api.vn/api/?depth=1');
    const data = await res.json();
    data.forEach(p => console.log(p.name));
}
run();
