const fs = require('fs');

async function build() {
    try {
        const response = await fetch('https://provinces.open-api.vn/api/?depth=3');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        let data = await response.json();
        if (data.value) data = data.value;

        // Bảng sáp nhập 34 đơn vị hành chính cấp tỉnh Việt Nam 2025
        const merges = {
            "Hà Nội": ["Hà Nội"],
            "Hải Phòng": ["Hải Phòng", "Hải Dương"],
            "Huế": ["Huế", "Thừa Thiên Huế", "Thừa Thiên - Huế"],
            "Đà Nẵng": ["Đà Nẵng", "Quảng Nam"],
            "TP. Hồ Chí Minh": ["Thành phố Hồ Chí Minh", "TP. Hồ Chí Minh", "Hồ Chí Minh", "Bà Rịa - Vũng Tàu", "Bình Dương"],
            "Cần Thơ": ["Cần Thơ", "Sóc Trăng", "Hậu Giang"],
            
            "Cao Bằng": ["Cao Bằng"],
            "Tuyên Quang": ["Tuyên Quang", "Hà Giang"],
            "Điện Biên": ["Điện Biên"],
            "Lai Châu": ["Lai Châu"],
            "Sơn La": ["Sơn La"],
            "Lào Cai": ["Lào Cai", "Yên Bái"],
            "Thái Nguyên": ["Thái Nguyên", "Bắc Kạn"],
            "Lạng Sơn": ["Lạng Sơn"],
            "Quảng Ninh": ["Quảng Ninh"],
            "Bắc Ninh": ["Bắc Ninh", "Bắc Giang"],
            "Phú Thọ": ["Phú Thọ", "Hòa Bình", "Hoà Bình", "Vĩnh Phúc"],
            "Hưng Yên": ["Hưng Yên", "Thái Bình"],
            "Ninh Bình": ["Ninh Bình", "Hà Nam", "Nam Định"],
            "Thanh Hóa": ["Thanh Hóa", "Thanh Hoá"],
            "Nghệ An": ["Nghệ An"],
            "Hà Tĩnh": ["Hà Tĩnh"],
            "Quảng Trị": ["Quảng Trị", "Quảng Bình"],
            "Quảng Ngãi": ["Quảng Ngãi", "Kon Tum"],
            "Gia Lai": ["Gia Lai", "Bình Định"],
            "Khánh Hòa": ["Khánh Hòa", "Ninh Thuận", "Khánh Hoà"],
            "Đắk Lắk": ["Đắk Lắk", "Phú Yên"],
            "Lâm Đồng": ["Lâm Đồng", "Đắk Nông", "Bình Thuận"],
            "Đồng Nai": ["Đồng Nai", "Bình Phước"],
            "Đồng Tháp": ["Đồng Tháp", "Tiền Giang"],
            "Tây Ninh": ["Tây Ninh", "Long An"],
            "An Giang": ["An Giang", "Kiên Giang"],
            "Cà Mau": ["Cà Mau", "Bạc Liêu"],
            "Vĩnh Long": ["Vĩnh Long", "Bến Tre", "Trà Vinh"]
        };

        const oldMap = {};
        data.forEach(p => {
            if(p.name) {
                let name = p.name.replace('Tỉnh ', '').replace('Thành phố ', '');
                if (name === 'Hồ Chí Minh') {
                    name = 'Thành phố Hồ Chí Minh';
                }
                oldMap[name] = p;
            }
        });

        const newProvinces = [];
        let codeCounter = 1;

        for (const [newName, oldNames] of Object.entries(merges)) {
            const mergedDistricts = [];
            let foundOldNamesCount = 0;

            oldNames.forEach(oldName => {
                const oldProvince = oldMap[oldName];
                if (oldProvince && oldProvince.districts) {
                    foundOldNamesCount++;
                    oldProvince.districts.forEach(d => {
                        let modifiedName = d.name;
                        // Determine if we need to suffix with oldName: if merges[newName] has more than 1 distinct province mapped
                        const isMergedGroup = oldNames.filter(n => oldMap[n]).length > 1;
                        if (isMergedGroup) {
                            modifiedName = d.name + ' (' + oldName + ')';
                        }
                        mergedDistricts.push({
                            name: modifiedName,
                            code: d.code,
                            codename: d.codename,
                            division_type: d.division_type,
                            short_codename: d.short_codename,
                            wards: d.wards
                        });
                    });
                } else {
                    // console.log('Warn: Missing data for old province:', oldName);
                }
            });
            
            if (foundOldNamesCount > 0) {
                let formattedName = newName;
                if (!['Hà Nội', 'Hải Phòng', 'Huế', 'Đà Nẵng', 'TP. Hồ Chí Minh', 'Cần Thơ'].includes(newName)) {
                    // Province
                }

                newProvinces.push({
                    name: formattedName,
                    code: codeCounter++,
                    codename: 'p_' + newName.toLowerCase().replace(/[\s-\.]/g, ''),
                    division_type: ['Hà Nội', 'Hải Phòng', 'Huế', 'Đà Nẵng', 'TP. Hồ Chí Minh', 'Cần Thơ'].includes(newName) ? 'thành phố trung ương' : 'tỉnh',
                    phone_code: 0,
                    districts: mergedDistricts
                });
            }
        }

        fs.writeFileSync('public/data.json', JSON.stringify(newProvinces, null, 2));
        console.log('Successfully generated public/data.json with ' + newProvinces.length + ' provinces.');
    } catch (e) {
        console.error(e.message);
    }
}
build();
