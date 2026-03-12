import sys
import subprocess

try:
    import pypdf
except ImportError:
    subprocess.check_call([sys.executable, "-m", "pip", "install", "pypdf", "--quiet", "--disable-pip-version-check"])
    import pypdf

try:
    reader = pypdf.PdfReader('f:/Project/hrm-php/MÔ TẢ CHỨC NĂNG APP WEBSITE HRM.pdf')
    for i, page in enumerate(reader.pages):
        print(f"--- Page {i+1} ---")
        text = page.extract_text()
        print(text if text else "(No text extracted)")
except Exception as e:
    print(f"Error loading PDF: {e}")
