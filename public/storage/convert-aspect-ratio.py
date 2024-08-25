from PIL import Image

def crop_center(image, width_ratio, height_ratio):
    """
    Crop the image to the given aspect ratio (width_ratio / height_ratio), centered in the image.
    
    :param image: The input image (PIL Image object).
    :param width_ratio: The width component of the desired aspect ratio.
    :param height_ratio: The height component of the desired aspect ratio.
    :return: The cropped image (PIL Image object).
    """
    img_width, img_height = image.size
    img_aspect_ratio = img_width / img_height
    target_aspect_ratio = width_ratio / height_ratio

    if img_aspect_ratio > target_aspect_ratio:
        # Image is wider than desired aspect ratio, crop the width
        new_width = int(img_height * target_aspect_ratio)
        new_height = img_height
    else:
        # Image is taller than desired aspect ratio, crop the height
        new_width = img_width
        new_height = int(img_width / target_aspect_ratio)

    left = (img_width - new_width) // 2
    top = (img_height - new_height) // 2
    right = left + new_width
    bottom = top + new_height

    return image.crop((left, top, right, bottom))

def process_images(input_folder, output_folder, width_ratio, height_ratio):
    """
    Process images in the input folder, crop them to the given aspect ratio.

    :param input_folder: Path to the folder with input images.
    :param output_folder: Path to the folder to save processed images.
    :param width_ratio: Width component of the desired aspect ratio.
    :param height_ratio: Height component of the desired aspect ratio.
    """
    import os

    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    for filename in os.listdir(input_folder):
        if filename.lower().endswith(('.png', '.jpg', '.jpeg')):
            input_path = os.path.join(input_folder, filename)
            output_path = os.path.join(output_folder, filename)

            try:
                with Image.open(input_path) as img:
                    cropped_image = crop_center(img, width_ratio, height_ratio)
                    cropped_image.save(output_path)
                    print(f"Processed and saved {filename} to {output_path}")
            except Exception as e:
                print(f"Error processing {filename}: {e}")

# Example usage
input_folder = "original"  # Replace with your input folder path
output_folder = "output"  # Replace with your output folder path
width_ratio = 1  # Width component of the desired aspect ratio
height_ratio = 1.155  # Height component of the desired aspect ratio

process_images(input_folder, output_folder, width_ratio, height_ratio)

