
namespace ARBiblioteket.ApiService.Dto
{
    public class ModelCreateDto
    {
        public string Title { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public string Education {  get; set; } = string.Empty;
        public string ThreeDFile { get; set; } = string.Empty;
        public string ImageFile { get; set; } = string.Empty;
        public int UploaderId { get; set; }
        public DateOnly Uploaded {  get; set; } 
    }

    public class ModelUpdateDto
    {
        public string Title { get; set; } = string.Empty;
        public string Description { get; set; } = string.Empty;
        public string Education { get; set; } = string.Empty;
        public string ThreeDFile { get; set; } = string.Empty;
        public string ImageFile { get; set; } = string.Empty;
        public DateOnly LastEdited { get; set; }
    }
}
