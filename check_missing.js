const fs = require('fs');

const generatedDataStr = fs.readFileSync('public/data.json', 'utf8');
const generatedData = JSON.parse(generatedDataStr);

const merges = [
    "Hà Nội", "Hải Phòng", "Huế", "Đà Nẵng", "TP. Hồ Chí Minh", "Cần Thơ",
    "Cao Bằng", "Tuyên Quang", "Điện Biên", "Lai Châu", "Sơn La", "Lào Cai", 
    "Thái Nguyên", "Lạng Sơn", "Quảng Ninh", "Bắc Ninh", "Phú Thọ", "Hưng Yên", 
    "Ninh Bình", "Thanh Hóa", "Nghệ An", "Hà Tĩnh", "Quảng Trị", "Quảng Ngãi", 
    "Gia Lai", "Khánh Hòa", "Đắk Lắk", "Lâm Đồng", "Đồng Nai", "Đồng Tháp", 
    "Tây Ninh", "An Giang", "Cà Mau", "Vĩnh Long"
];

const generatedNames = generatedData.map(p => p.name);

merges.forEach(n => {
    if (!generatedNames.includes(n)) {
        console.log("Missing:", n);
    }
});
