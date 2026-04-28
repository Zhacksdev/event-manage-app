from datetime import datetime
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()


class Notification(db.Model):
    __tablename__ = 'notifications'

    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    registration_id = db.Column(db.String(36), nullable=False, index=True)
    user_id = db.Column(db.BigInteger, nullable=False, index=True)
    type = db.Column(db.String(50), nullable=False)  # 'registration_confirmed', 'event_reminder'
    message = db.Column(db.Text, nullable=False)
    sent_at = db.Column(db.DateTime, default=datetime.utcnow)
    status = db.Column(db.String(20), default='sent')  # 'sent', 'failed'

    def to_dict(self):
        return {
            'id': self.id,
            'registration_id': self.registration_id,
            'user_id': self.user_id,
            'type': self.type,
            'message': self.message,
            'sent_at': self.sent_at.isoformat() if self.sent_at else None,
            'status': self.status,
        }
