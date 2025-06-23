# Admin Dashboard Features

## Overview
Dashboard admin telah diperbarui dengan fitur statistik dan visualisasi data yang komprehensif untuk mendukung monitoring website Xplorea.

## Fitur Utama

### 1. Statistik Cards
- **Total Users**: Jumlah total pengguna terdaftar
- **Total Artists**: Jumlah total seniman
- **Total Artworks**: Jumlah total karya seni
- **Pending Approvals**: Seniman yang menunggu persetujuan
- **Approved Artists**: Seniman yang sudah disetujui
- **Total Events**: Jumlah total event
- **Approved Artworks**: Karya seni yang disetujui
- **Pending Artworks**: Karya seni yang menunggu persetujuan
- **Rejected Artworks**: Karya seni yang ditolak

### 2. Chart Visualisasi

#### User Growth Chart
- Menampilkan pertumbuhan pengguna dalam 6 bulan terakhir
- Line chart dengan area fill
- Dropdown untuk memilih periode (6 bulan, 12 bulan, tahunan)
- Auto-refresh setiap 5 menit

#### Artworks Statistics Chart
- Doughnut chart menampilkan distribusi status karya seni
- Kategori: Approved, Pending, Rejected
- Tooltip dengan persentase
- Warna yang berbeda untuk setiap kategori

#### Monthly Artworks Growth Chart
- Menampilkan pertumbuhan karya seni per bulan
- Line chart dengan area fill
- Responsive design

### 3. Statistik Tambahan
- **Artist Conversion Rate**: Persentase pengguna yang menjadi seniman
- **Average Artworks per Artist**: Rata-rata karya seni per seniman
- **Artwork Approval Rate**: Persentase karya seni yang disetujui
- **New Users This Month**: Pengguna baru bulan ini

### 4. Quick Actions
- Review Artist Approvals
- Manage Users
- Manage Artists
- System Settings
- Manage Events
- Add New Event
- Manage Artworks
- Generate Reports
- Analytics
- Print Dashboard

### 5. System Status
- Progress bar untuk approval rate
- Progress bar untuk pending approvals
- Status sistem

## Teknologi yang Digunakan

### Frontend
- **Chart.js**: Untuk visualisasi data
- **Bootstrap 5**: Untuk layout dan styling
- **Font Awesome**: Untuk ikon
- **Vanilla JavaScript**: Untuk interaktivitas

### Backend
- **CodeIgniter 4**: Framework PHP
- **MySQL**: Database
- **AJAX**: Untuk update data real-time

## File yang Dimodifikasi

### Views
- `app/Views/admin/dashboard.php`: Dashboard utama

### Controllers
- `app/Controllers/AdminController.php`: Logic dashboard dan chart data

### Routes
- `app/Config/Routes.php`: Route untuk AJAX endpoint

### JavaScript
- `public/js/admin-dashboard.js`: Chart management dan interaktivitas

## Cara Penggunaan

### 1. Akses Dashboard
```
/admin/dashboard
```

### 2. Interaksi dengan Charts
- Hover pada chart untuk melihat detail
- Klik dropdown untuk mengubah periode
- Charts akan auto-refresh setiap 5 menit

### 3. Export dan Print
- Klik tombol "Print Dashboard" untuk mencetak
- Charts dapat di-export sebagai gambar (fitur tersedia di JavaScript)

## API Endpoints

### Chart Data
```
GET /admin/dashboard/chart-data?period=6months
```

Response:
```json
{
    "userGrowth": [12, 19, 15, 25, 22, 30],
    "artworksGrowth": [5, 12, 8, 18, 15, 25],
    "artworks": {
        "approved": 150,
        "pending": 25,
        "rejected": 10
    }
}
```

## Konfigurasi

### Auto-refresh Interval
Ubah interval auto-refresh di `public/js/admin-dashboard.js`:
```javascript
setInterval(() => {
    this.refreshData();
}, 5 * 60 * 1000); // 5 menit
```

### Chart Colors
Warna chart dapat diubah di `public/js/admin-dashboard.js`:
```javascript
borderColor: 'rgb(75, 192, 192)',
backgroundColor: 'rgba(75, 192, 192, 0.2)'
```

## Responsive Design

Dashboard telah dioptimalkan untuk berbagai ukuran layar:
- Desktop: Layout penuh dengan semua fitur
- Tablet: Layout yang disesuaikan
- Mobile: Layout yang dioptimalkan untuk layar kecil

## Performance

- Charts menggunakan Chart.js yang ringan
- Data di-cache untuk performa yang lebih baik
- Lazy loading untuk chart
- Optimized queries untuk database

## Troubleshooting

### Chart tidak muncul
1. Pastikan Chart.js CDN ter-load
2. Periksa console browser untuk error
3. Pastikan data tersedia di controller

### Data tidak update
1. Periksa koneksi internet
2. Periksa endpoint AJAX
3. Periksa log server

### Layout rusak
1. Pastikan Bootstrap CSS ter-load
2. Periksa responsive breakpoints
3. Clear cache browser

## Future Enhancements

1. **Real-time Updates**: WebSocket untuk update real-time
2. **More Chart Types**: Bar chart, radar chart
3. **Export Features**: PDF, Excel export
4. **Custom Date Range**: Date picker untuk periode custom
5. **Drill-down**: Klik chart untuk detail lebih lanjut
6. **Notifications**: Push notification untuk event penting
7. **Dark Mode**: Tema gelap untuk dashboard
8. **Multi-language**: Dukungan bahasa tambahan

## Support

Untuk pertanyaan atau masalah terkait dashboard, silakan hubungi tim development atau buat issue di repository. 