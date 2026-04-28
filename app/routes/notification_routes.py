from flask import Blueprint
from app.controllers.notification_controller import (
    create_notification,
    get_all_notifications,
    get_notification_by_id,
    get_notifications_by_user,
)

notification_bp = Blueprint('notifications', __name__)

# CONSUMER endpoint
notification_bp.add_url_rule('/notify', view_func=create_notification, methods=['POST'])

# PROVIDER endpoints
notification_bp.add_url_rule('/notifications', view_func=get_all_notifications, methods=['GET'])
notification_bp.add_url_rule('/notifications/<int:notif_id>', view_func=get_notification_by_id, methods=['GET'])
notification_bp.add_url_rule('/notifications/user/<int:user_id>', view_func=get_notifications_by_user, methods=['GET'])
