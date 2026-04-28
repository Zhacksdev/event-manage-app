import os
from flask import Flask, jsonify
from flask_cors import CORS
from dotenv import load_dotenv

from app.models.notification import db
from app.routes.notification_routes import notification_bp

load_dotenv()


def create_app():
    app = Flask(__name__)

    # ── MySQL Config ─────────────────────────────
    DB_HOST = os.getenv('DB_HOST', 'localhost')
    DB_PORT = os.getenv('DB_PORT', '3306')
    DB_USER = os.getenv('DB_USER', 'root')
    DB_PASS = os.getenv('DB_PASS', '')
    DB_NAME = os.getenv('DB_NAME', 'notifications_db')

    app.config['SQLALCHEMY_DATABASE_URI'] = (
        f'mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}:{DB_PORT}/{DB_NAME}'
    )
    app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
    app.config['JSON_SORT_KEYS'] = False

    # ── Init ─────────────────────────────────────
    CORS(app)
    db.init_app(app)

    # ── Routes ───────────────────────────────────
    app.register_blueprint(notification_bp, url_prefix='/api')
    

    # ── Health check ─────────────────────────────
    @app.get('/')
    def root():
        return jsonify({
            'service': 'NotificationService',
            'version': '1.0.0',
            'status': 'running',
            'port': int(os.getenv('PORT', 8003)),
        })

    # ── 404 handler ──────────────────────────────
    @app.errorhandler(404)
    def not_found(e):
        return jsonify({'success': False, 'message': 'Route not found'}), 404

    # ── Global error handler ─────────────────────
    @app.errorhandler(500)
    def server_error(e):
        return jsonify({'success': False, 'message': 'Internal server error'}), 500

    # ── Auto-create tables ───────────────────────
    with app.app_context():
        try:
            db.create_all()
            print(f'[DB] MySQL connected to {DB_NAME}')
        except Exception as e:
            print(f'[DB] Connection failed: {e}')
            raise

    return app


if __name__ == '__main__':
    port = int(os.getenv('PORT', 8003))
    app = create_app()
    print(f'[SERVER] NotificationService running on http://localhost:{port}')
    app.run(host='0.0.0.0', port=port, debug=True)
