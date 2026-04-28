from flask import request, jsonify
import requests

from app.models.notification import db, Notification
from clients.registration_client import get_registration_by_id


# ─────────────────────────────────────────────────────
# CONSUMER: POST /api/notify
# Dipanggil oleh RegistrationService setelah pendaftaran berhasil.
# Consume RegistrationService untuk ambil detail registrasi.
# ─────────────────────────────────────────────────────
def create_notification():
    data = request.get_json(silent=True) or {}

    registration_id = data.get('registration_id')
    user_id = data.get('user_id')
    notif_type = data.get('type', 'registration_confirmed')

    if not registration_id or not user_id:
        return jsonify({
            'success': False,
            'message': 'registration_id and user_id are required'
        }), 400

    # ── Consume RegistrationService ──────────────
    try:
        reg_response = get_registration_by_id(registration_id)
        # RegistrationService return shape bisa { success, data: {...} } atau langsung object
        reg_data = reg_response.get('data') if isinstance(reg_response, dict) and 'data' in reg_response else reg_response

        user_name = reg_data.get('user_name', 'Mahasiswa')
        event_title = reg_data.get('event_title', 'Event')
        status = reg_data.get('status', 'pending')

    except requests.exceptions.RequestException as e:
        # Tetap simpan notifikasi dengan status 'failed' kalau RegistrationService down
        failed = Notification(
            registration_id=registration_id,
            user_id=int(user_id),
            type=notif_type,
            message=f'Failed to fetch registration detail: {str(e)}',
            status='failed',
        )
        db.session.add(failed)
        db.session.commit()
        return jsonify({
            'success': False,
            'message': 'RegistrationService unavailable',
            'error': str(e),
            'data': failed.to_dict(),
        }), 503

    # ── Compose message berdasarkan type ─────────
    if notif_type == 'registration_confirmed':
        message = (
            f'Halo {user_name}, pendaftaranmu untuk event "{event_title}" '
            f'telah {status}. Terima kasih sudah mendaftar!'
        )
    elif notif_type == 'event_reminder':
        message = (
            f'Halo {user_name}, jangan lupa event "{event_title}" '
            f'akan segera dimulai. Sampai jumpa!'
        )
    else:
        message = f'Halo {user_name}, ada update untuk registrasi event "{event_title}".'

    # ── Simpan notifikasi ────────────────────────
    notif = Notification(
        registration_id=registration_id,
        user_id=int(user_id),
        type=notif_type,
        message=message,
        status='sent',
    )
    db.session.add(notif)
    db.session.commit()

    return jsonify({
        'success': True,
        'message': 'Notification sent successfully',
        'data': notif.to_dict(),
    }), 201


# ─────────────────────────────────────────────────────
# PROVIDER: GET /api/notifications
# ─────────────────────────────────────────────────────
def get_all_notifications():
    notifs = Notification.query.order_by(Notification.sent_at.desc()).all()
    return jsonify({
        'success': True,
        'count': len(notifs),
        'data': [n.to_dict() for n in notifs],
    }), 200


# ─────────────────────────────────────────────────────
# PROVIDER: GET /api/notifications/{id}
# ─────────────────────────────────────────────────────
def get_notification_by_id(notif_id):
    notif = Notification.query.get(notif_id)
    if not notif:
        return jsonify({'success': False, 'message': 'Notification not found'}), 404

    return jsonify({'success': True, 'data': notif.to_dict()}), 200


# ─────────────────────────────────────────────────────
# PROVIDER: GET /api/notifications/user/{userId}
# ─────────────────────────────────────────────────────
def get_notifications_by_user(user_id):
    notifs = (
        Notification.query
        .filter_by(user_id=user_id)
        .order_by(Notification.sent_at.desc())
        .all()
    )
    return jsonify({
        'success': True,
        'count': len(notifs),
        'data': [n.to_dict() for n in notifs],
    }), 200
