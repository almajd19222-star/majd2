<?php
$show_popup = false;
$updated_records = [];
$not_updated_records = [];
$not_found_records = [];

if (isset($_SESSION['show_tracking_result']) && $_SESSION['show_tracking_result'] === true) {
    $show_popup = true;
    $updated_records = isset($_SESSION['tracking_updated']) ? $_SESSION['tracking_updated'] : [];
    $not_updated_records = isset($_SESSION['tracking_not_updated']) ? $_SESSION['tracking_not_updated'] : [];
    $not_found_records = isset($_SESSION['tracking_not_found']) ? $_SESSION['tracking_not_found'] : [];

    unset($_SESSION['show_tracking_result']);
    unset($_SESSION['tracking_updated']);
    unset($_SESSION['tracking_not_updated']);
    unset($_SESSION['tracking_not_found']);
}
?>

<?php if ($show_popup): ?>
<style>
    .tracking-modal-overlay {
        display: flex;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        direction: rtl;
    }
    .tracking-modal-content {
        background: #fff;
        width: 90%;
        max-width: 700px;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .tracking-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .tracking-modal-header h2 { margin:0; color:#007bff; }
    .tracking-close { font-size: 30px; cursor:pointer; color:#999; }
    .tracking-close:hover { color:#000; }
    .tracking-section { margin-bottom: 20px; }
    .tracking-section-title {
        padding: 10px 15px; border-radius: 5px; font-weight: bold; margin-bottom: 10px; display:flex; align-items:center; gap:10px;
    }
    .tracking-section-title.success { background:#d4edda; color:#155724; border-left:4px solid #28a745; }
    .tracking-section-title.warning { background:#fff3cd; color:#856404; border-left:4px solid #ffc107; }
    .tracking-section-title.danger { background:#f8d7da; color:#721c24; border-left:4px solid #dc3545; }
    .tracking-table { width:100%; border-collapse:collapse; }
    .tracking-table th, .tracking-table td { padding:12px; text-align:right; border-bottom:1px solid #ddd; }
    .tracking-table th { background:#f1f1f1; }
    .tracking-badge {
        display:inline-block; padding:5px 10px; border-radius:20px; font-size:12px; font-weight:bold;
    }
    .tracking-badge.success { background:#28a745; color:#fff; }
    .tracking-badge.warning { background:#ffc107; color:#333; }
    .tracking-badge.danger { background:#dc3545; color:#fff; }
    .tracking-close-btn { background:#007bff; color:#fff; border:none; padding:10px 25px; border-radius:5px; cursor:pointer; }
    .tracking-empty { text-align:center; color:#666; padding:15px; }
</style>

<div class="tracking-modal-overlay" id="trackingModal">
    <div class="tracking-modal-content">
        <div class="tracking-modal-header">
            <h2>نتائج تحديث جدول المتابعة</h2>
            <span class="tracking-close" onclick="closeTrackingModal()">&times;</span>
        </div>

        <?php if (count($updated_records) > 0): ?>
        <div class="tracking-section">
            <div class="tracking-section-title success">✅ السجلات المحدثة بنجاح (<?php echo count($updated_records); ?>)</div>
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الحالة الجديدة</th>
                        <th>رقم الكتاب</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($updated_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span class="tracking-badge success"><?php echo htmlspecialchars($record['new_status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><?php echo htmlspecialchars($record['ketab_num'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['ketab_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if (count($not_updated_records) > 0): ?>
        <div class="tracking-section">
            <div class="tracking-section-title warning">⚠️ السجلات الموجودة لكن لم يتم تحديثها (<?php echo count($not_updated_records); ?>)</div>
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الحالة الحالية</th>
                        <th>السبب</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($not_updated_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span class="tracking-badge warning"><?php echo htmlspecialchars($record['current_status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><?php echo htmlspecialchars($record['reason'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if (count($not_found_records) > 0): ?>
        <div class="tracking-section">
            <div class="tracking-section-title danger">❌ الأسماء غير الموجودة في جدول المتابعة (<?php echo count($not_found_records); ?>)</div>
            <table class="tracking-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($not_found_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span class="tracking-badge danger"><?php echo htmlspecialchars($record['reason'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php if (count($updated_records) == 0 && count($not_updated_records) == 0 && count($not_found_records) == 0): ?>
            <div class="tracking-empty">لم يتم العثور على أي بيانات لتحديثها في جدول المتابعة</div>
        <?php endif; ?>

        <div style="text-align:center; margin-top:20px;">
            <button class="tracking-close-btn" onclick="closeTrackingModal()">إغلاق</button>
        </div>
    </div>
</div>

<script>
    function closeTrackingModal() {
        var modal = document.getElementById('trackingModal');
        if (modal) modal.style.display = 'none';
    }
    window.onclick = function(event) {
        var modal = document.getElementById('trackingModal');
        if (event.target == modal) modal.style.display = 'none';
    }
    window.onload = function() {
        var modal = document.getElementById('trackingModal');
        if (modal) modal.style.display = 'flex';
    }
</script>
<?php endif; ?>
