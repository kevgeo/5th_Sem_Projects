/***********************************************
     Sobel edge detection with Parallelisation
************************************************/

#include <iostream>
#include <bits/stdc++.h>
#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/opencv.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include <opencv2/objdetect/objdetect.hpp>
#include <math.h>
#include <omp.h>     

using namespace cv; 
using namespace std; 

int main()
{
    string filename;
    cout<<"Enter filename:";
    cin>> filename;
    int normalize_val=8;    
    cout<<"\nEnter normalization value:";
    cin>> normalize_val;

	// Reading image
    Mat img = imread(filename);

    // Displaying image
	imshow("Original Image",img);
	waitKey(0);

	// Converting to grayscale
	Mat img_gray,image_blur;
	GaussianBlur( img, image_blur, Size(3,3), 3, 3);
    cvtColor(image_blur,img_gray,CV_RGB2GRAY);

	
    // Displaying grayscale image
	//imshow("Original Image",img_gray);
	//waitKey(0);

	
	int cols = img_gray.cols;
	int rows = img_gray.rows;

	// Creating sobel operator in x direction
	int sobel_x[3][3] = {-1,0,1,-2,0,2,-1,0,1};
	// Creating sobel operator in y direction
	int sobel_y[3][3] = {-1,-2,-1,0,0,0,1,2,1};


	int radius = 1;
	
	// Handle border issues
    Mat _src;
    copyMakeBorder(img_gray, _src, radius, radius, radius, radius, BORDER_REFLECT101);

    // Create output matrix
    Mat gradient_x = img_gray.clone();
    Mat gradient_y = img_gray.clone();
    Mat gradient_f = img_gray.clone();

    int max=0;

	// Correlation loop in x direction 
    
    // Iterate on image 
    int r,c,i,j,s;
    //cout<<_src.rows;
    double start,end;
    start = omp_get_wtime();

        #pragma omp parallel for  default(shared)  private(r,c,i,j,s) num_threads(4) ordered schedule(dynamic,120) collapse(2) 
            for (r = radius; r < _src.rows - radius; ++r)
            {
                for (c = radius; c < _src.cols - radius; ++c)
                {
                    s = 0;

                    // Iterate on kernel
                    //#pragma omp parallel for  default(shared)  private(r,c,i,j) num_threads(3) ordered collapse(2) schedule(static,3)
                    for (i = -radius; i <= radius; ++i)
                    {
                        for (j = -radius; j <= radius; ++j)
                        {
                            s += _src.at<uchar>(r + i, c + j) * sobel_x[i + radius][j + radius];
                        }
                    }
                    gradient_x.at<uchar>(r - radius, c - radius) = s/normalize_val;

                    /*if(s>200)
                    	gradient.at<uchar>(r - radius, c - radius) = 255;
                    else
                      	gradient.at<uchar>(r - radius, c - radius) = 0;
                    */    
                }
            }


    end = omp_get_wtime();

    double diff = end-start;
    cout<<"Time for parallel code:"<<diff<<endl;

    Mat absGrad_x;
    convertScaleAbs( gradient_x, absGrad_x );

    // Conrrelation loop in y direction 
    
    // Iterate on image
    #pragma omp parallel for  default(shared)  private(r,c,i,j) num_threads(4) ordered schedule(static,120) collapse(2) reduction(+:s)
    for (int r = radius; r < _src.rows - radius; ++r)
    {
        for (int c = radius; c < _src.cols - radius; ++c)
        {
            int s = 0;

            // Iterate on kernel
            for (int i = -radius; i <= radius; ++i)
            {
                for (int j = -radius; j <= radius; ++j)
                {
                    s += _src.at<uchar>(r + i, c + j) * sobel_y[i + radius][j + radius];
                }
            }
        
            gradient_y.at<uchar>(r - radius, c - radius) = s/normalize_val;

            /*if(s>200)
                gradient.at<uchar>(r - radius, c - radius) = 255;
            else
                gradient.at<uchar>(r - radius, c - radius) = 0;
            */    
        }
    }

    Mat absGrad_y;
    convertScaleAbs( gradient_y, absGrad_y );
    
    Mat absGrad =img_gray.clone(); ;
    for(int i=0; i<absGrad_y.rows; i++)
    {
        for(int j=0; j<absGrad_y.cols; j++)
        {
            absGrad.at<uchar>(i,j) = sqrt( pow(absGrad_x.at<uchar>(i,j),2) + pow(absGrad_y.at<uchar>(i,j),2) );  
        
             if(absGrad.at<uchar>(i,j) >250)
                absGrad.at<uchar>(i,j) = 255;
            else
                absGrad.at<uchar>(i,j) = 0;
        }
    }
    


    //Calculating gradient magnitude
    for(int i=0; i<gradient_f.rows; i++)
    {
        for(int j=0; j<gradient_f.cols; j++)
        {
            gradient_f.at<uchar>(i,j) = sqrt( pow(gradient_x.at<uchar>(i,j),2) + pow(gradient_y.at<uchar>(i,j),2) );  
        
             if(gradient_f.at<uchar>(i,j) >250)
                gradient_f.at<uchar>(i,j) = 150;
            else
                gradient_f.at<uchar>(i,j) = 0;
                
        }
    }
    
   

    /*
    imshow("grad x",gradient_x);
	waitKey(0);

    
    imshow("grad y",gradient_y);
    waitKey(0);
    */

    imshow("grad magnitude",gradient_f);
    waitKey(0);

    //imshow("absolute grad magnitude",absGrad);
    //waitKey(0);	
    
    
    /*cv::Mat Gx, Gy; int ksize=3;
    Mat abs_grad_x, abs_grad_y;
    cv::Sobel(img_gray, Gx, CV_8U, 1, 0, ksize);
    convertScaleAbs( Gx, abs_grad_x );
    cv::Sobel(img_gray, Gy, CV_8U, 0, 1, ksize);
    convertScaleAbs( Gy, abs_grad_y );
    Mat grad;
    addWeighted( abs_grad_x, 0.5, abs_grad_y, 0.5, 0, grad );
    */
    //imshow("Sobel Image",grad);
    //waitKey(0);
    
	return 0;

}
