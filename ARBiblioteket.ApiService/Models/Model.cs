namespace ARBiblioteket.ApiService.Models
{
    public class Model
    {
        public int Id { get; set; }
        public string Title { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public string Education { get; set; } = string.Empty;
        public string ThreeDFile { get; set; } = string.Empty;
        public string ImageFile { get; set; } = string.Empty;
        public int UploaderId { get; set; }
        public DateOnly Uploaded { get; set; }
        public DateOnly LastEdited { get; set; }
    }
}