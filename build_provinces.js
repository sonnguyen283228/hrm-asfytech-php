const fs = require('fs');

async function build() {
    try {
        const response = await fetch('https://provinces.open-api.vn/api/?depth=3');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        let data = await response.json();
        if (data.value) data = data.value;

        const merges = {
            "Hà Nội": ["Hà Nội"],
            "TP. Hồ Chí Minh": ["Thành phố Hồ Chí Minh"],
            "Hải Phòng": ["Hải Phòng", "Hải Dương", "Hưng Yên", "Thái Bình"],
            "Đà Nẵng": ["Đà Nẵng", "Quảng Nam", "Quảng Ngãi"],
            "Cần Thơ": ["Cần Thơ", "Sóc Trăng", "Hậu Giang"],
            "Huế": ["Thừa Thiên Huế", "Thừa Thiên - Huế", "Quảng Trị", "Quảng Bình"],
            
            "Cao Bằng": ["Cao Bằng"],
            "Điện Biên": ["Điện Biên"],
            "Hà Tĩnh": ["Hà Tĩnh"],
            "Lai Châu": ["Lai Châu"],
            "Lạng Sơn": ["Lạng Sơn"],
            "Nghệ An": ["Nghệ An"],
            "Quảng Ninh": ["Quảng Ninh"],
            "Thanh Hóa": ["Thanh Hóa", "Thanh Hoá"],
            "Sơn La": ["Sơn La"],

            "Hòa Bình - Thái Nguyên": ["Hòa Bình", "Hoà Bình", "Thái Nguyên", "Tuyên Quang", "Bắc Kạn"],
            "Lào Cai - Hà Giang": ["Lào Cai", "Hà Giang", "Yên Bái", "Phú Thọ"],
            "Vĩnh Phúc - Bắc Ninh": ["Vĩnh Phúc", "Bắc Ninh", "Bắc Giang"],
            "Hà Nam - Ninh Bình": ["Hà Nam", "Nam Định", "Ninh Bình"],
            
            "Tây Ninh - Bình Dương": ["Tây Ninh", "Bình Dương", "Bình Phước"],
            "Đồng Nai - Bà Rịa": ["Đồng Nai", "Bà Rịa - Vũng Tàu", "Bình Thuận"],
            
            "Khu vực Tây Nguyên": ["Kon Tum", "Gia Lai", "Đắk Lắk", "Đắk Nông"],
            "Lâm Đồng - Ninh Thuận": ["Lâm Đồng", "Ninh Thuận", "Khánh Hòa", "Khánh Hoà"],
            
            "Khu vực Nam Trung Bộ": ["Phú Yên", "Bình Định"],
            
            "Long An - Tiền Giang": ["Long An", "Tiền Giang", "Bến Tre"],
            "Đồng Tháp - Vĩnh Long": ["Đồng Tháp", "Vĩnh Long", "Trà Vinh"],
            "An Giang - Kiên Giang": ["An Giang", "Kiên Giang"],
            "Bạc Liêu - Cà Mau": ["Bạc Liêu", "Cà Mau"]
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
                newProvinces.push({
                    name: newName,
                    code: codeCounter++,
                    codename: 'p_' + newName.toLowerCase().replace(/[\s-\.]/g, ''),
                    division_type: 'tỉnh',
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
