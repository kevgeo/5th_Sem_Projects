
#include <opencv2\opencv.hpp>
#include <iostream>
using namespace std;
using namespace cv;


void my_sobel(const Mat1b& src, Mat1s& dst, int direction)
{
    Mat1s kernel;
    int radius = 0;

    // Create the kernel
    if (direction == 0)
    {
        // Sobel 3x3 X kernel
        kernel = (Mat1s(3,3) << -1, 0, +1, -2, 0, +2, -1, 0, +1);
        radius = 1;
    }
    else
    {
        // Sobel 3x3 Y kernel
        kernel = (Mat1s(3, 3) << -1, -2, -1, 0, 0, 0, +1, +2, +1);
        radius = 1;
    }

    // Handle border issues
    Mat1b _src;
    copyMakeBorder(src, _src, radius, radius, radius, radius, BORDER_REFLECT101);

    // Create output matrix
    dst.create(src.rows, src.cols);

    // Convolution loop 

    // Iterate on image 
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            short s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src(r + i, c + j) * kernel(i + radius, j + radius);
                }
            }
            dst(r - radius, c - radius) = s;
        }
    }
}
    // Convolution loop coding by me

    // Iterate on image 
    for (int r = 0; r < _src.rows-radius; ++r)
    {
        for (int c = 0; c < _src.cols- radius; ++c)
        {
            short s = 0;

            // Iterate on kernel
            for (int i = 0; i <= 2; ++i)
            {
                for (int j = 0; j <= 2; ++j)
                {
                    s += _src(r+i,c+j) * kernel(i , j);
                }
            }
            dst(r,c) = s;
        }
    }


int main(void)
{
    Mat1b img = imread("path_to_image", IMREAD_GRAYSCALE);

    // Compute custom Sobel 3x3 derivatives
    Mat1s sx, sy;
    my_sobel(img, sx, 0);
    my_sobel(img, sy, 1);

    // Edges L1 norm
    Mat1b edges_L1;
    absdiff(sx, sy, edges_L1);


    // Check results against OpenCV
    Mat1s cvsx,cvsy;
    Sobel(img, cvsx, CV_16S, 1, 0);
    Sobel(img, cvsy, CV_16S, 0, 1);
    Mat1b cvedges_L1;
    absdiff(cvsx, cvsy, cvedges_L1);

    Mat diff_L1;
    absdiff(edges_L1, cvedges_L1, diff_L1);

    cout << "Number of different pixels: " << countNonZero(diff_L1) << endl;

    return 0;
}