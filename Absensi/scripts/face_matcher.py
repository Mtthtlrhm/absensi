import sys
import face_recognition
import json

def get_face_encoding(image_path):
    try:
        image = face_recognition.load_image_file(image_path)
        face_encodings = face_recognition.face_encodings(image)
        if len(face_encodings) > 0:
            encoding = face_encodings[0].tolist()
            print(json.dumps(encoding))
        else:
            pass
    except Exception as e:
        pass

if __name__ == "__main__":
    if len(sys.argv) > 1:
        get_face_encoding(sys.argv[1])